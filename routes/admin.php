<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SmsGatewayController;
use App\Http\Controllers\Admin\SocialLoginController;
use App\Http\Controllers\Admin\PlanPurchaseController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\CityController;

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getChartData'])->name('admin.dashboard.data');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Users
    Route::resource('users', UserController::class, ['except' => ['create', 'store']])->names('admin.users');
    Route::get('users/{user}/plans', [UserController::class, 'showPlans'])->name('admin.users.plans');

    // Properties
    Route::get('properties', [PropertyController::class, 'index'])->name('admin.properties.index');
    Route::get('properties/{property}', [PropertyController::class, 'show'])->name('admin.properties.show');
    Route::get('properties/{property}/edit', [PropertyController::class, 'edit'])->name('admin.properties.edit');
    Route::put('properties/{property}', [PropertyController::class, 'update'])->name('admin.properties.update');
    Route::post('properties/{property}/disable', [PropertyController::class, 'disable'])->name('admin.properties.disable');
    Route::post('properties/{property}/re-approve', [PropertyController::class, 'reApprove'])->name('admin.properties.re-approve');
    Route::post('properties/{property}/re-enable', [PropertyController::class, 'reEnable'])->name('admin.properties.re-enable');
    Route::delete('properties/{property}', [PropertyController::class, 'destroy'])->name('admin.properties.destroy');
    Route::get('properties/{property}/versions', [PropertyController::class, 'versions'])->name('admin.properties.versions');
    Route::post('versions/{version}/approve', [PropertyController::class, 'approveVersion'])->name('admin.versions.approve');
    Route::post('versions/{version}/reject', [PropertyController::class, 'rejectVersion'])->name('admin.versions.reject');
    Route::post('versions/{version}/cancel', [PropertyController::class, 'cancelVersion'])->name('admin.versions.cancel');
    Route::post('versions/bulk-approve', [PropertyController::class, 'bulkApprove'])->name('admin.versions.bulk-approve');
    Route::post('versions/bulk-reject', [PropertyController::class, 'bulkReject'])->name('admin.versions.bulk-reject');
    Route::get('versions/{version}/diff', [PropertyController::class, 'diff'])->name('admin.versions.diff');

    // Categories Management
    Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Subcategory Management
    Route::post('subcategories', [CategoryController::class, 'storeSubcategory'])->name('admin.subcategories.store');
    Route::put('subcategories/{subcategory}', [CategoryController::class, 'updateSubcategory'])->name('admin.subcategories.update');
    Route::delete('subcategories/{subcategory}', [CategoryController::class, 'destroySubcategory'])->name('admin.subcategories.destroy');


    // Plans Management
    Route::resource('plans', PlanController::class)->names('admin.plans');

    // Plan Purchases Management
    Route::get('plan-purchases', [PlanPurchaseController::class, 'index'])->name('admin.plan-purchases.index');
    Route::get('plan-purchases/{planPurchase}', [PlanPurchaseController::class, 'show'])->name('admin.plan-purchases.show');
    Route::post('plan-purchases/{planPurchase}/activate', [PlanPurchaseController::class, 'activate'])->name('admin.plan-purchases.activate');
    Route::post('plan-purchases/{planPurchase}/expire', [PlanPurchaseController::class, 'expire'])->name('admin.plan-purchases.expire');
    Route::post('plan-purchases/{planPurchase}/approve', [PlanPurchaseController::class, 'approve'])->name('admin.plan-purchases.approve');
    Route::post('plan-purchases/{planPurchase}/reject', [PlanPurchaseController::class, 'reject'])->name('admin.plan-purchases.reject');

    // Payments Management
    Route::get('payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('admin.payments.show');

    // Payment Gateways
    Route::get('payment-gateways', [PaymentGatewayController::class, 'index'])->name('admin.payment-gateways.index');
    Route::post('payment-gateways', [PaymentGatewayController::class, 'update'])->name('admin.payment-gateways.update');

    // Payment Approvals
    Route::get('payments/pending', [PaymentController::class, 'pendingApprovals'])->name('admin.payments.pending');
    Route::post('payments/{payment}/approve', [PaymentController::class, 'approvePayment'])->name('admin.payments.approve');
    Route::post('payments/{payment}/reject', [PaymentController::class, 'rejectPayment'])->name('admin.payments.reject');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('settings/test-email', [SettingController::class, 'testEmail'])->name('admin.settings.test-email');

    // SMS Gateways
    Route::get('sms-gateways', [SmsGatewayController::class, 'index'])->name('admin.sms-gateways.index');
    Route::post('sms-gateways', [SmsGatewayController::class, 'update'])->name('admin.sms-gateways.update');

    // Social Logins
    Route::get('social-logins', [SocialLoginController::class, 'index'])->name('admin.social-logins.index');
    Route::post('social-logins', [SocialLoginController::class, 'update'])->name('admin.social-logins.update');

    // Contact Messages
    Route::get('contact-messages', [AdminContactController::class, 'index'])->name('admin.contact-messages.index');
    Route::delete('contact-messages/{contactMessage}', [AdminContactController::class, 'destroy'])->name('admin.contact-messages.destroy');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('notifications/count', [NotificationController::class, 'count'])->name('admin.notifications.count');
    Route::get('notifications/dropdown', [NotificationController::class, 'dropdown'])->name('admin.notifications.dropdown');
    Route::post('notifications/mark-seen', [NotificationController::class, 'markAsSeen'])->name('admin.notifications.mark-seen');
    Route::delete('notifications/{notification}', [NotificationController::class, 'delete'])->name('admin.notifications.delete');

    // Email Logs
    Route::get('email-logs', [EmailLogController::class, 'index'])->name('admin.email-logs.index');
    Route::post('email-logs/{id}/resend', [EmailLogController::class, 'resend'])->name('admin.email-logs.resend');

    // States Management
    Route::get('states', [StateController::class, 'index'])->name('admin.states.index');
    Route::post('states', [StateController::class, 'store'])->name('admin.states.store');
    Route::put('states/{state}', [StateController::class, 'update'])->name('admin.states.update');
    Route::delete('states/{state}', [StateController::class, 'destroy'])->name('admin.states.destroy');

    // Districts Management
    Route::get('districts', [DistrictController::class, 'index'])->name('admin.districts.index');
    Route::post('districts', [DistrictController::class, 'store'])->name('admin.districts.store');
    Route::put('districts/{district}', [DistrictController::class, 'update'])->name('admin.districts.update');
    Route::delete('districts/{district}', [DistrictController::class, 'destroy'])->name('admin.districts.destroy');

    // Cities Management
    Route::get('cities', [CityController::class, 'index'])->name('admin.cities.index');
    Route::post('cities', [CityController::class, 'store'])->name('admin.cities.store');
    Route::put('cities/{city}', [CityController::class, 'update'])->name('admin.cities.update');
    Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('admin.cities.destroy');

});