<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\OtpService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendForgotPasswordOtp(Request $request): RedirectResponse
    {
        // Auto-prefix 91 to mobile number if 10 digits
        if ($request->mobile && preg_match('/^\d{10}$/', $request->mobile)) {
            $request->merge(['mobile' => '91' . $request->mobile]);
        }

        $request->validate([
            'mobile' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
        ]);

        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $otp = $this->otpService->generateOtp();
        $result = $this->otpService->sendOtpToMobile($request->mobile, $otp, 'forgot_password');

        if ($result['success']) {
            session(['forgot_password_mobile' => $request->mobile]);
            return redirect()->back()->with('success', 'OTP sent to your mobile number');
        }

        return redirect()->back()->with('error', 'Failed to send OTP');
    }

    public function verifyForgotPasswordOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!$this->otpService->verifyOtpFromSession($request->otp)) {
            return redirect()->back()->with('error', 'Invalid or expired OTP');
        }

        session(['forgot_password_verified' => true]);
        return redirect()->route('password.reset.form');
    }

    public function showResetPasswordForm(): View
    {
        if (!session('forgot_password_verified') || !session('forgot_password_mobile')) {
            abort(401, 'Unauthorized');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        if (!session('forgot_password_verified') || !session('forgot_password_mobile')) {
            abort(401, 'Unauthorized');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('mobile', session('forgot_password_mobile'))->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found');
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Clear session
        session()->forget(['forgot_password_mobile', 'forgot_password_verified']);

        return redirect()->route('login')->with('success', 'Password reset successfully');
    }
}