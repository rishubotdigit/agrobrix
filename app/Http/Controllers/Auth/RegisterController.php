<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\OtpService;
use App\Models\User;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Events\UserRegistered;
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
        ini_set('max_execution_time', 120);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,owner,agent,buyer',
        ]);

        // Check if OTP verification is enabled
        $otpEnabled = Setting::get('otp_verification_enabled') == '1';

        if ($otpEnabled) {
            // Store registration data in session
            session(['registration_data' => $request->all()]);
            
            // Generate OTP
            $otp = $this->otpService->generateOtp();
            
            // Send OTP
            $result = $this->otpService->sendOtpToMobile($request->mobile, $otp);
            
            if ($result['success']) {
                if ($request->ajax()) {
                    // Return success but NO redirect to trigger OTP modal on frontend
                    return response()->json([
                        'success' => true, 
                        'message' => 'OTP sent successfully. Please verify your mobile number.'
                    ]);
                }
                return redirect()->route('register.verify.otp.form');
            } else {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Failed to send OTP: ' . ($result['error'] ?? 'Unknown error')], 500);
                }
                return back()->withErrors(['mobile' => 'Failed to send OTP. Please try again.']);
            }
        }

        // Create user directly (OTP Disabled)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
            'verified_at' => now(),
        ]);

        \Illuminate\Support\Facades\Log::info('User created in register method', ['user_id' => $user->id, 'role' => $user->role, 'request_role' => $request->role]);

        $this->assignBasicPlanIfApplicable($user);

        UserRegistered::dispatch($user);

        // Log in the user
        Auth::login($user);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Registration completed successfully!', 'redirect' => $this->getRedirectUrl($user)]);
        }
        return redirect($this->getRedirectUrl($user))->with('success', 'Registration completed successfully!');
    }

    public function showVerifyOtpForm()
    {
        if (!session('registration_data')) {
            return redirect()->route('register');
        }
        $otpEnabled = Setting::get('otp_verification_enabled') == '1';
        return view('auth.verify-otp', compact('otpEnabled'));
    }

    public function verifyOtp(Request $request)
    {
        ini_set('max_execution_time', 120);

        $otpEnabled = Setting::get('otp_verification_enabled') == '1';

        $request->validate([
            'otp' => $otpEnabled ? 'required|string|size:6' : 'nullable',
        ]);

        $registrationData = session('registration_data');
        if (!$registrationData) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Session expired'], 400);
            }
            return redirect()->route('register');
        }

        if (!$otpEnabled || $this->otpService->verifyOtpFromSession($request->otp)) {
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

            \Illuminate\Support\Facades\Log::info('User created in verifyOtp method', ['user_id' => $user->id, 'role' => $user->role, 'session_role' => $registrationData['role']]);

            $this->assignBasicPlanIfApplicable($user);

            UserRegistered::dispatch($user);

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

    private function assignBasicPlanIfApplicable(User $user): void
    {
        if (!in_array($user->role, ['owner', 'agent'])) {
            return;
        }

        $basicPlan = Plan::where('name', 'Basic')
            ->where('role', $user->role)
            ->first();

        if (!$basicPlan) {
            \Illuminate\Support\Facades\Log::warning('Basic plan not found for role', ['role' => $user->role, 'user_id' => $user->id]);
            return;
        }

        // Deactivate any existing active plans for the user
        $activeCount = PlanPurchase::where('user_id', $user->id)
            ->where('status', 'activated')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->count();

        PlanPurchase::where('user_id', $user->id)
            ->where('status', 'activated')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->update(['status' => 'deactivated']);

        \Illuminate\Support\Facades\Log::info('Deactivated active plans on registration', [
            'user_id' => $user->id,
            'active_plans_deactivated' => $activeCount
        ]);

        // Create new PlanPurchase for Basic plan
        $purchase = PlanPurchase::create([
            'user_id' => $user->id,
            'plan_id' => $basicPlan->id,
            'status' => 'activated',
            'activated_at' => now(),
            'expires_at' => now()->addDays($basicPlan->getValidityDays()),
            'used_contacts' => 0,
            'used_featured_listings' => 0,
        ]);

        \Illuminate\Support\Facades\Log::info('Basic plan assigned on registration', [
            'user_id' => $user->id,
            'plan_id' => $basicPlan->id,
            'purchase_id' => $purchase->id
        ]);
    }

    private function getRedirectUrl(User $user): string
    {
        if (request()->has('plan') && request()->get('plan')) {
            return route('plans.purchase', request()->get('plan'));
        }
        
        if ($user->role === 'owner' || $user->role === 'agent') {
            return route('owner.dashboard');
        }

        if ($user->role === 'admin') {
            return route('admin.dashboard');
        }

        return route('home');
    }
}