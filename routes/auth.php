<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/register/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('register.verify.otp.form');
    Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->name('register.verify.otp');
    Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend.otp');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


// Social Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

    Route::get('/auth/role-selection', [SocialAuthController::class, 'showRoleSelection'])->name('auth.role.selection');
    Route::post('/auth/role-selection', [SocialAuthController::class, 'storeRoleSelection'])->name('auth.role.selection.store');
});