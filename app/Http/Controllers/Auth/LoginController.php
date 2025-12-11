<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\OtpService;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_type' => 'required|in:email,mobile',
            'email' => 'required_if:login_type,email|email',
            'mobile' => 'required_if:login_type,mobile|string|regex:/^\+?[1-9]\d{1,14}$/',
            'password' => 'required_if:login_type,email|string',
        ]);

        if ($request->login_type === 'email') {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                if (!$user->isVerified()) {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Please verify your account first.']);
                }
                $intended = session('url.intended', $this->getRedirectUrl($user));
                return redirect($intended)->with('success', 'Login successful! Welcome back.');
            }
            return back()->withErrors(['email' => 'Invalid credentials']);
        } else {
            // Mobile login - send OTP
            $user = User::where('mobile', $request->mobile)->first();
            if (!$user) {
                return back()->withErrors(['mobile' => 'User not found']);
            }

            if ($this->otpService->sendOtpToUser($user)) {
                session(['otp_mobile' => $request->mobile]);
                return redirect()->route('otp.verify.form');
            }
            return back()->withErrors(['mobile' => 'Failed to send OTP']);
        }
    }

    public function showOtpVerifyForm()
    {
        if (!session('otp_mobile')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($this->otpService->sendOtpToUser($user)) {
            return response()->json(['message' => 'OTP sent successfully']);
        }

        return response()->json(['error' => 'Failed to send OTP'], 500);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($this->otpService->verifyOtp($user, $request->otp)) {
            // Log the user in
            auth()->login($user);
            $intended = session('url.intended', $this->getRedirectUrl($user));
            return response()->json(['message' => 'OTP verified successfully! Welcome back.', 'redirect' => $intended]);
        }

        return response()->json(['error' => 'Invalid or expired OTP'], 400);
    }

    public function resendOtp(Request $request)
    {
        $mobile = session('otp_mobile');
        if (!$mobile) {
            return response()->json(['error' => 'Session expired'], 400);
        }

        $user = User::where('mobile', $mobile)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($this->otpService->canResendOtp($user)) {
            if ($this->otpService->sendOtpToUser($user)) {
                return response()->json(['message' => 'OTP resent successfully']);
            } else {
                return response()->json(['error' => 'Failed to send OTP'], 500);
            }
        } else {
            $now = now();
            $remaining = 0;
            if ($user->otp_resend_count >= 3) {
                $remaining = 3600 - $now->diffInSeconds($user->last_otp_resend_at);
            } elseif ($user->last_otp_resend_at) {
                $remaining = 60 - $now->diffInSeconds($user->last_otp_resend_at);
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