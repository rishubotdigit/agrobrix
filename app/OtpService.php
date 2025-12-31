<?php

namespace App;

use Twilio\Rest\Client;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use App\Models\SmsTemplate;
use App\Models\SmsLog;

class OtpService
{
    protected $twilio;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');

        if (!$sid || !$token) {
            $this->twilio = null;
            return;
        }

        $this->twilio = new Client($sid, $token);
    }

    public function generateOtp(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendOtp(string $mobile, string $otp): array
    {
        if (!$this->twilio) {
            Log::error('Twilio client not initialized: missing credentials');
            return ['success' => false, 'response' => null, 'error' => 'Twilio client not initialized: missing credentials'];
        }

        try {
            $message = $this->twilio->messages->create(
                $mobile,
                [
                    'from' => config('services.twilio.from'),
                    'body' => "Your OTP code is: {$otp}. Valid for 5 minutes."
                ]
            );
            return ['success' => true, 'response' => $message->toArray(), 'error' => null];
        } catch (\Exception $e) {
            dd($e);
            Log::error('OTP sending failed: ' . $e->getMessage());
            return ['success' => false, 'response' => null, 'error' => $e->getMessage()];
        }
    }

    public function verifyOtp(User $user, string $otp): bool
    {
        if (Setting::get('otp_verification_enabled') != '1') {
            return false;
        }

        $gateway = $this->getGateway();
        if ($gateway === '2factor') {
            if ($this->verifyOtp2Factor($user->otp_session_id, $otp)) {
                $user->update(['verified_at' => now()]);
                return true;
            }
            return false;
        } else {
            if ($user->otp_code === $otp && !$user->isOtpExpired()) {
                $user->update([
                    'otp_code' => null,
                    'otp_expiry' => null,
                    'verified_at' => now(),
                ]);
                return true;
            }
            return false;
        }
    }

    public function getGateway(): string
    {
        return Setting::get('sms_gateway', 'twilio');
    }

    public function sendOtp2Factor(string $mobile, string $otp): array
    {
        $template = Setting::get('otp_template', '{otp} is Your OTP for Mobile Number verification');
        $msg = str_replace('{otp}', $otp, $template);
        try {
            $response = Http::post('https://2factor.in/API/R1/', [
                'module' => 'TRANS_SMS',
                'apikey' => Setting::get('2factor_api_key'),
                'to' => $mobile,
                'from' => Setting::get('sender_id'),
                'msg' => $msg,
                'peid' => Setting::get('entity_id'),
                'ctid' => Setting::get('template_id'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $success = isset($data['Status']) && $data['Status'] === 'Success';
                return ['success' => $success, 'response' => $data, 'error' => null];
            } else {
                return ['success' => false, 'response' => $response->json() ?? null, 'error' => 'HTTP error'];
            }
        } catch (\Exception $e) {
            Log::error('2Factor OTP sending failed: ' . $e->getMessage());
            return ['success' => false, 'response' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send OTP using MSG91 Flow API
     */
    public function sendOtpMsg91(string $mobile, string $otp, string $templateSlug = 'otp', $userId = null): array
    {
        // Create log entry
        $log = SmsLog::create([
            'user_id' => $userId,
            'mobile' => $mobile,
            'message' => "OTP: {$otp}",
            'template_slug' => $templateSlug,
            'gateway' => 'msg91',
            'status' => 'pending',
            'type' => 'otp',
        ]);

        try {
            // Get template from database
            $template = SmsTemplate::getBySlug($templateSlug, 'msg91');
            
            if (!$template) {
                $error = 'Template not found: ' . $templateSlug;
                $log->update(['status' => 'failed', 'error' => $error]);
                Log::error('MSG91 template not found: ' . $templateSlug);
                return ['success' => false, 'response' => null, 'error' => $error];
            }

            $authkey = Setting::get('msg91_authkey');
            
            if (!$authkey) {
                $error = 'MSG91 authkey not configured';
                $log->update(['status' => 'failed', 'error' => $error]);
                Log::error($error);
                return ['success' => false, 'response' => null, 'error' => $error];
            }

            // Build payload according to MSG91 flow API
            $payload = [
                'template_id' => $template->template_id,
                'recipients' => [
                    [
                        'mobiles' => $mobile,
                        'var1' => $otp, // Default OTP variable
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'authkey' => $authkey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://control.msg91.com/api/v5/flow', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $log->update(['status' => 'sent', 'response' => $data]);
                Log::info('MSG91 OTP sent successfully', ['response' => $data]);
                return ['success' => true, 'response' => $data, 'error' => null];
            } else {
                $errorData = $response->json() ?? ['message' => 'Unknown error'];
                $error = 'HTTP error: ' . $response->status();
                $log->update(['status' => 'failed', 'response' => $errorData, 'error' => $error]);
                Log::error('MSG91 OTP sending failed', ['response' => $errorData]);
                return ['success' => false, 'response' => $errorData, 'error' => $error];
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $log->update(['status' => 'failed', 'error' => $error]);
            Log::error('MSG91 OTP sending failed: ' . $error);
            return ['success' => false, 'response' => null, 'error' => $error];
        }
    }

    public function verifyOtp2Factor(string $session_id, string $otp): bool
    {
        try {
            $response = Http::get("https://2factor.in/API/V1/" . Setting::get('2factor_api_key') . "/SMS/VERIFY/{$session_id}/{$otp}");

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['Status']) && $data['Status'] === 'Success';
            }
            return false;
        } catch (\Exception $e) {
            Log::error('2Factor OTP verification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function canResendOtp(User $user): bool
    {
        $now = now();
        $resendLimit = Setting::get('otp_resend_limit', 3);
        if ($user->last_otp_resend_at && $user->last_otp_resend_at->diffInHours($now) >= 1) {
            $user->update(['otp_resend_count' => 0]);
        }
        return (!$user->last_otp_resend_at || $user->last_otp_resend_at->diffInMinutes($now) >= 1) && $user->otp_resend_count < $resendLimit;
    }

    public function sendOtpToUser(User $user): array
    {
        if (Setting::get('otp_verification_enabled') != '1') {
            return ['success' => false, 'response' => null, 'error' => 'OTP verification not enabled'];
        }

        $isResend = !is_null($user->otp_code);
        $otp = $isResend ? $user->otp_code : $this->generateOtp();
        $gateway = $this->getGateway();

        $result = null;
        if ($gateway === '2factor') {
            $result = $this->sendOtp2Factor($user->mobile, $otp);
        } elseif ($gateway === 'msg91') {
            $result = $this->sendOtpMsg91($user->mobile, $otp, 'otp', $user->id);
        } else {
            $result = $this->sendOtp($user->mobile, $otp);
        }

        if ($result['success']) {
            // Handle session ID for 2Factor
            if ($gateway === '2factor') {
                $user->otp_session_id = $result['response']['Details'] ?? null;
                $user->save();
            }
            // Note: MSG91 flow API doesn't return a session ID for verification
            // OTP verification will be done locally by comparing the OTP codes
            
            if (!$isResend) {
                $user->update([
                    'otp_code' => $otp,
                    'otp_expiry' => now()->addMinutes(Setting::get('otp_expiry_time', 5)),
                    'otp_resend_count' => 1,
                    'last_otp_resend_at' => now(),
                ]);
            } else {
                $user->increment('otp_resend_count');
                $user->update(['last_otp_resend_at' => now()]);
            }
        }

        return $result;
    }

    public function sendOtpToMobile(string $mobile, string $otp): array
    {
        if (Setting::get('otp_verification_enabled') != '1') {
            return ['success' => false, 'response' => null, 'error' => 'OTP verification not enabled'];
        }

        $gateway = $this->getGateway();
      
        $result = null;
        if ($gateway === '2factor') {
            $result = $this->sendOtp2Factor($mobile, $otp);
            if ($result['success']) {
                Session::put('otp_session_id', $result['response']['Details'] ?? null);
            }
        } elseif ($gateway === 'msg91') {
            $result = $this->sendOtpMsg91($mobile, $otp, 'otp');
            if ($result['success']) {
                Session::put('otp_code', $otp);
                Session::put('otp_expiry', now()->addMinutes(Setting::get('otp_expiry_time', 5)));
            }
        } else {
            $result = $this->sendOtp($mobile, $otp);
            if ($result['success']) {
                Session::put('otp_code', $otp);
                Session::put('otp_expiry', now()->addMinutes(Setting::get('otp_expiry_time', 5)));
            }
        }
        return $result;
    }

    public function verifyOtpFromSession(string $otp): bool
    {
        if (Setting::get('otp_verification_enabled') != '1') {
            return false;
        }

        $gateway = $this->getGateway();
        if ($gateway === '2factor') {
            $session_id = Session::get('otp_session_id');
            if ($session_id && $this->verifyOtp2Factor($session_id, $otp)) {
                Session::forget('otp_session_id');
                return true;
            }
        } else {
            $stored_otp = Session::get('otp_code');
            $expiry = Session::get('otp_expiry');
            if ($stored_otp === $otp && $expiry && now()->lessThanOrEqualTo($expiry)) {
                Session::forget(['otp_code', 'otp_expiry']);
                return true;
            }
        }
        return false;
    }

    public function canResendOtpFromSession(): bool
    {
        $now = now();
        $resendLimit = Setting::get('otp_resend_limit', 3);
        $lastResendAt = Session::get('last_otp_resend_at');
        $resendCount = Session::get('otp_resend_count', 0);

        if ($lastResendAt && $lastResendAt->diffInHours($now) >= 1) {
            Session::put('otp_resend_count', 0);
            $resendCount = 0;
        }

        return (!$lastResendAt || $lastResendAt->diffInMinutes($now) >= 1) && $resendCount < $resendLimit;
    }

    public function resendOtpFromSession(string $mobile): bool
    {
        if (Setting::get('otp_verification_enabled') != '1') {
            return false;
        }

        if (!$this->canResendOtpFromSession()) {
            return false;
        }

        $gateway = $this->getGateway();
        $otp = Session::get('otp_code'); // Reuse the same OTP for resend
        if (!$otp) {
            $otp = $this->generateOtp();
            Session::put('otp_code', $otp);
            Session::put('otp_expiry', now()->addMinutes(Setting::get('otp_expiry_time', 5)));
        }

        $sent = false;
        if ($gateway === '2factor') {
            $result = $this->sendOtp2Factor($mobile, $otp);
            if ($result['success']) {
                Session::put('otp_session_id', $result['response']['Details'] ?? null);
                $sent = true;
            }
        } elseif ($gateway === 'msg91') {
            $result = $this->sendOtpMsg91($mobile, $otp, 'otp');
            $sent = $result['success'];
        } else {
            $result = $this->sendOtp($mobile, $otp);
            $sent = $result['success'];
        }

        if ($sent) {
            $resendCount = Session::get('otp_resend_count', 0) + 1;
            Session::put('otp_resend_count', $resendCount);
            Session::put('last_otp_resend_at', now());
        }

        return $sent;
    }
}