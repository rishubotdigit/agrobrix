<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/register/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('register.verify.otp.form');
    Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->name('register.verify.otp');
    Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend.otp');
    Route::get('/verify-otp', [LoginController::class, 'showOtpVerifyForm'])->name('otp.verify.form');
    Route::post('/login/resend-otp', [LoginController::class, 'resendOtp'])->name('login.resend.otp');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// OTP routes
Route::middleware(['guest', 'throttle:otp'])->group(function () {
    Route::post('/otp/send', [LoginController::class, 'sendOtp'])->name('otp.send');
    Route::post('/otp/verify', [LoginController::class, 'verifyOtp'])->name('otp.verify');
});