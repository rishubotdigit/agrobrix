<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buyer\DashboardController;
use App\Http\Controllers\Buyer\ProfileController;
use App\Http\Controllers\Buyer\SearchController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Buyer\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanPurchaseController;
use App\Http\Controllers\InquiryController;

Route::prefix('buyer')->middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('buyer.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('buyer.dashboard.stats');
    Route::get('/inquiries', [DashboardController::class, 'inquiries'])->name('buyer.inquiries');
    Route::get('/plans', [DashboardController::class, 'plans'])->name('buyer.plans');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('buyer.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('buyer.profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'requestDeletion'])->name('buyer.profile.delete');

    Route::get('/properties', [SearchController::class, 'properties'])->name('buyer.properties');
    Route::get('/api/properties', [SearchController::class, 'getPropertiesApi'])->name('buyer.api.properties');
    Route::get('/api/property-options', [SearchController::class, 'getPropertyOptions'])->name('buyer.api.property-options');
    Route::get('/search', [SearchController::class, 'index'])->name('buyer.search.index');

    Route::get('/properties/{property}/contact', [InquiryController::class, 'viewContact'])->name('buyer.properties.contact');

    // Wishlist (Saved Properties)
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('buyer.wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('buyer.wishlist.add');
    Route::delete('/wishlist/remove/{propertyId}', [WishlistController::class, 'remove'])->name('buyer.wishlist.remove');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('buyer.notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('buyer.notifications.count');
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('buyer.notifications.dropdown');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsSeen'])->name('buyer.notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAsSeen'])->name('buyer.notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('buyer.notifications.delete');

    // Plan Purchases
    Route::get('/plan-purchases', [PlanPurchaseController::class, 'index'])->name('buyer.plan-purchases.index');
    Route::get('/plan-purchases/{planPurchase}', [PlanPurchaseController::class, 'show'])->name('buyer.plan-purchases.show');
    Route::post('/plans/{plan}/purchase', [PlanPurchaseController::class, 'initiatePurchase'])->name('buyer.plans.purchase');

});

// Payment routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payments/contact/{property}', [PaymentController::class, 'initiateContactPayment'])->name('payments.contact');
    Route::post('/payments/success', [PaymentController::class, 'paymentSuccess'])->name('payments.success');
    Route::post('/payments/submit-transaction-id', [PaymentController::class, 'submitTransactionId'])->name('payments.submit-transaction-id');
    Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook')->withoutMiddleware(['auth', 'csrf']);
});