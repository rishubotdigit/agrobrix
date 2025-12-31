<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Buyer\SearchController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\InquiryController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/states', [HomeController::class, 'allStates'])->name('states.all');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/search', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::get('/plans/{plan}/purchase', [PlanController::class, 'purchase'])->name('plans.purchase')->middleware('auth');
Route::post('/payment/verify', [PlanController::class, 'verifyPayment'])->name('payment.verify')->middleware('auth');
Route::get('/payment/success/{payment}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success')->middleware('auth');
Route::get('/payment/static-success/{payment}', [\App\Http\Controllers\PaymentController::class, 'staticSuccess'])->name('payment.static.success')->middleware('auth');
Route::get('/payment/failure', [\App\Http\Controllers\PaymentController::class, 'failure'])->name('payment.failure')->middleware('auth');

Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/careers', [HomeController::class, 'careers'])->name('careers');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/for-buyers', [HomeController::class, 'forBuyers'])->name('for-buyers');
Route::get('/for-sellers', [HomeController::class, 'forSellers'])->name('for-sellers');
Route::get('/post-property', [HomeController::class, 'postProperty'])->name('post-property');
Route::get('/roles', [HomeController::class, 'roles'])->name('roles');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');
Route::get('/contact/redirect-login', function () {
    session()->flash('message', 'Please login to view the contact details.');
    return redirect()->route('login');
})->name('contact.redirect.login');
Route::get('/refund', [HomeController::class, 'refund'])->name('refund');

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendForgotPasswordOtp'])->name('password.send.otp');
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyForgotPasswordOtp'])->name('password.verify.otp');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('/properties/{property}/inquiry/submit', [InquiryController::class, 'submitInquiry'])->name('inquiry.submit');
    Route::post('/properties/{property}/inquiry/verify-otp', [InquiryController::class, 'verifyOtp'])->name('inquiry.verifyOtp');
    Route::post('/properties/{property}/inquiry/status', [InquiryController::class, 'checkInquiryStatus'])->name('inquiry.status');
    Route::post('/properties/{property}/contact', [\App\Http\Controllers\InquiryController::class, 'viewContact'])->name('properties.contact');
    Route::post('/wishlist/add', [\App\Http\Controllers\Buyer\WishlistController::class, 'add'])->middleware('role:buyer')->name('wishlist.add');
    Route::delete('/wishlist/remove/{propertyId}', [\App\Http\Controllers\Buyer\WishlistController::class, 'remove'])->middleware('role:buyer')->name('wishlist.remove');
});

require __DIR__.'/auth.php';

// Include role-based routes
require __DIR__.'/admin.php';
require __DIR__.'/owner.php';
require __DIR__.'/agent.php';
require __DIR__.'/buyer.php';
