<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ProfileController;
use App\Http\Controllers\Owner\MyPropertyController;
use App\Http\Controllers\Owner\NotificationController;
use App\Http\Controllers\PlanPurchaseController;

Route::prefix('owner')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('owner.dashboard.stats');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('owner.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('owner.profile.update');

    // Property CRUD routes
    Route::get('/properties', [MyPropertyController::class, 'index'])->name('owner.properties.index');
    Route::get('/properties/create', [MyPropertyController::class, 'create'])->name('owner.properties.create');
    Route::post('/properties', [MyPropertyController::class, 'store'])->name('owner.properties.store');
    Route::get('/properties/{property}', [MyPropertyController::class, 'show'])->name('owner.properties.show');
    Route::get('/properties/{property}/edit', [MyPropertyController::class, 'edit'])->name('owner.properties.edit');
    Route::put('/properties/{property}', [MyPropertyController::class, 'update'])->name('owner.properties.update');
    Route::delete('/properties/{property}', [MyPropertyController::class, 'destroy'])->name('owner.properties.destroy');
    Route::post('/properties/{property}/assign-agent', [MyPropertyController::class, 'assignAgent'])->name('owner.properties.assignAgent');
    Route::post('/properties/{property}/unassign-agent', [MyPropertyController::class, 'unassignAgent'])->name('owner.properties.unassignAgent');
    Route::post('/properties/{property}/feature', [MyPropertyController::class, 'featureProperty'])->name('owner.properties.feature');
    Route::post('/properties/{property}/unfeature', [MyPropertyController::class, 'unfeatureProperty'])->name('owner.properties.unfeature');


    // Plan Purchases
    Route::get('/plan-purchases', [PlanPurchaseController::class, 'index'])->name('owner.plan-purchases.index');
    Route::get('/plan-purchases/{planPurchase}', [PlanPurchaseController::class, 'show'])->name('owner.plan-purchases.show');
    Route::post('/plans/{plan}/purchase', [PlanPurchaseController::class, 'initiatePurchase'])->name('owner.plans.purchase');

    // Plan details
    Route::get('/plan', [DashboardController::class, 'plan'])->name('owner.plan');

    // Payments
    Route::get('/payments', [DashboardController::class, 'payments'])->name('owner.payments');

    // Leads
    Route::get('/leads', [DashboardController::class, 'leads'])->name('owner.leads');
    Route::get('/leads/{lead}', [DashboardController::class, 'showLead'])->name('owner.leads.show');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('owner.notifications.index');
    Route::get('notifications/count', [NotificationController::class, 'count'])->name('owner.notifications.count');
    Route::get('notifications/dropdown', [NotificationController::class, 'dropdown'])->name('owner.notifications.dropdown');
    Route::post('notifications/mark-seen', [NotificationController::class, 'markAsSeen'])->name('owner.notifications.mark-seen');
    Route::delete('notifications/{notification}', [NotificationController::class, 'delete'])->name('owner.notifications.delete');
    });