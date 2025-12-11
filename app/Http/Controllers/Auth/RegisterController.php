<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\OtpService;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,owner,agent,buyer',
        ]);

        $otpEnabled = Setting::get('otp_verification_enabled') == '1';

        if (!$otpEnabled) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(),
                'verified_at' => now(),
            ]);

            // User is registered but not logged in

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Registration completed successfully! Please log in.', 'redirect' => route('login')]);
            }

            return redirect(route('login'))->with('success', 'Registration completed successfully! Please log in.');
        }

        // Store registration data in session
        session(['registration_data' => $request->only(['name', 'email', 'mobile', 'password', 'role'])]);

        // Generate and send OTP
        $otp = $this->otpService->generateOtp();

        $result = $this->otpService->sendOtpToMobile($request->mobile, $otp);
        if ($result['success']) {
            // Initialize resend tracking
            session(['otp_resend_count' => 1, 'last_otp_resend_at' => now()]);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'OTP sent successfully', 'api_response' => $result]);
            }
            return redirect()->route('register.verify.otp.form');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP', 'api_response' => $result], 500);
        }
        return back()->withErrors(['mobile' => 'Failed to send OTP']);
    }

    public function showVerifyOtpForm()
    {
        if (!session('registration_data')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $registrationData = session('registration_data');
        if (!$registrationData) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Session expired'], 400);
            }
            return redirect()->route('register');
        }

        if ($this->otpService->verifyOtpFromSession($request->otp)) {
            // Create user from session data
            $user = User::create([
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
                'mobile' => $registrationData['mobile'],
                'password' => Hash::make($registrationData['password']),
                'role' => $registrationData['role'],
                'email_verified_at' => now(),
                'verified_at' => now(),
            ]);

            // Log in the user
            Auth::login($user);
            // Clear session
            session()->forget(['registration_data', 'otp_code', 'otp_expiry', 'otp_session_id', 'otp_resend_count', 'last_otp_resend_at']);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Registration completed successfully!', 'redirect' => $this->getRedirectUrl($user)]);
            }
            return redirect($this->getRedirectUrl($user))->with('success', 'Registration completed successfully!');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
        }
        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }

    public function resendOtp(Request $request)
    {
        $registrationData = session('registration_data');
        if (!$registrationData) {
            return response()->json(['error' => 'Session expired'], 400);
        }

        $mobile = $registrationData['mobile'];

        if ($this->otpService->canResendOtpFromSession()) {
            if ($this->otpService->resendOtpFromSession($mobile)) {
                return response()->json(['message' => 'OTP resent successfully']);
            } else {
                return response()->json(['error' => 'Failed to send OTP'], 500);
            }
        } else {
            $now = now();
            $remaining = 0;
            $resendCount = session('otp_resend_count', 0);
            $lastResendAt = session('last_otp_resend_at');
            if ($resendCount >= 3) {
                $remaining = 3600 - $now->diffInSeconds($lastResendAt);
            } elseif ($lastResendAt) {
                $remaining = 60 - $now->diffInSeconds($lastResendAt);
            }
            $remaining = max(0, $remaining);
            return response()->json(['error' => 'Too many requests. Try again in ' . $remaining . ' seconds.'], 429);
        }
    }

    private function getRedirectUrl(User $user): string
    {
        return match($user->role) {
            'admin' => route('admin.dashboard'),
            'owner' => route('owner.dashboard'),
            'agent' => route('agent.dashboard'),
            'buyer' => route('buyer.dashboard'),
            default => '/',
        };
    }
}