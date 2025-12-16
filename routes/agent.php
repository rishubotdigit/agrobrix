<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Agent\LeadController;
use App\Http\Controllers\Agent\PropertyController;
use App\Http\Controllers\Agent\VisitController;
use App\Http\Controllers\Agent\FollowUpController;
use App\Http\Controllers\Agent\NotificationController;
use App\Http\Controllers\PlanPurchaseController;

Route::prefix('agent')->middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('agent.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('agent.dashboard.stats');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('agent.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('agent.profile.update');

    // Leads
    Route::get('/leads', [LeadController::class, 'index'])->name('agent.leads.index');
    Route::get('/leads/create', [LeadController::class, 'create'])->name('agent.leads.create');
    Route::post('/leads', [LeadController::class, 'store'])->name('agent.leads.store');
    Route::get('/leads/{id}', [LeadController::class, 'show'])->name('agent.leads.show');
    Route::get('/leads/{id}/edit', [LeadController::class, 'edit'])->name('agent.leads.edit');
    Route::put('/leads/{id}', [LeadController::class, 'update'])->name('agent.leads.update');
    Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('agent.leads.destroy');

    // Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('agent.properties.index');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('agent.properties.show');
    Route::post('/properties/{property}/feature', [PropertyController::class, 'featureProperty'])->name('agent.properties.feature');
    Route::post('/properties/{property}/unfeature', [PropertyController::class, 'unfeatureProperty'])->name('agent.properties.unfeature');
    
    // My Properties
    Route::get('/my-properties', [App\Http\Controllers\Agent\MyPropertyController::class, 'index'])->name('agent.my-properties.index');
    Route::get('/my-properties/create', [App\Http\Controllers\Agent\MyPropertyController::class, 'create'])->name('agent.my-properties.create');
    Route::post('/my-properties', [App\Http\Controllers\Agent\MyPropertyController::class, 'store'])->name('agent.my-properties.store');
    Route::get('/my-properties/{property}', [App\Http\Controllers\Agent\MyPropertyController::class, 'show'])->name('agent.my-properties.show');
    Route::get('/my-properties/{property}/edit', [App\Http\Controllers\Agent\MyPropertyController::class, 'edit'])->name('agent.my-properties.edit');
    Route::put('/my-properties/{property}', [App\Http\Controllers\Agent\MyPropertyController::class, 'update'])->name('agent.my-properties.update');
    Route::delete('/my-properties/{property}', [App\Http\Controllers\Agent\MyPropertyController::class, 'destroy'])->name('agent.my-properties.destroy');
    Route::post('my-properties/{property}/feature', [App\Http\Controllers\Agent\MyPropertyController::class, 'featureProperty']);
    Route::post('my-properties/{property}/unfeature', [App\Http\Controllers\Agent\MyPropertyController::class, 'unfeatureProperty']);
    
    // Visits
    Route::get('/visits', [VisitController::class, 'index'])->name('agent.visits.index');
    Route::get('/leads/{leadId}/visits/create', [VisitController::class, 'create'])->name('agent.visits.create');
    Route::post('/leads/{leadId}/visits', [VisitController::class, 'store'])->name('agent.visits.store');
    Route::get('/visits/{id}', [VisitController::class, 'show'])->name('agent.visits.show');
    Route::get('/visits/{id}/edit', [VisitController::class, 'edit'])->name('agent.visits.edit');
    Route::put('/visits/{id}', [VisitController::class, 'update'])->name('agent.visits.update');
    Route::delete('/visits/{id}', [VisitController::class, 'destroy'])->name('agent.visits.destroy');

    // Follow-ups
    Route::get('/follow-ups', [FollowUpController::class, 'index'])->name('agent.follow-ups.index');
    Route::get('/leads/{leadId}/follow-ups/create', [FollowUpController::class, 'create'])->name('agent.follow-ups.create');
    Route::post('/leads/{leadId}/follow-ups', [FollowUpController::class, 'store'])->name('agent.follow-ups.store');
    Route::get('/follow-ups/{id}', [FollowUpController::class, 'show'])->name('agent.follow-ups.show');
    Route::get('/follow-ups/{id}/edit', [FollowUpController::class, 'edit'])->name('agent.follow-ups.edit');
    Route::put('/follow-ups/{id}', [FollowUpController::class, 'update'])->name('agent.follow-ups.update');
    Route::delete('/follow-ups/{id}', [FollowUpController::class, 'destroy'])->name('agent.follow-ups.destroy');
    
    // Plan Purchases
    Route::get('/plan-purchases', [PlanPurchaseController::class, 'index'])->name('agent.plan-purchases.index');
    Route::get('/plan-purchases/{planPurchase}', [PlanPurchaseController::class, 'show'])->name('agent.plan-purchases.show');
    Route::post('/plans/{plan}/purchase', [PlanPurchaseController::class, 'initiatePurchase'])->name('agent.plans.purchase');
    
    // Plan details
    Route::get('/plan', [DashboardController::class, 'plan'])->name('agent.plan');

    // Payments
    Route::get('/payments', [DashboardController::class, 'payments'])->name('agent.payments');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('agent.notifications.index');
    Route::get('notifications/count', [NotificationController::class, 'count'])->name('agent.notifications.count');
    Route::get('notifications/dropdown', [NotificationController::class, 'dropdown'])->name('agent.notifications.dropdown');
    Route::post('notifications/mark-seen', [NotificationController::class, 'markAsSeen'])->name('agent.notifications.mark-seen');
    Route::delete('notifications/{notification}', [NotificationController::class, 'delete'])->name('agent.notifications.delete');

});