<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_id',
        'status',
        'activated_at',
        'expires_at',
        'used_contacts',
        'used_featured_listings',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the plan purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that was purchased.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the payment for this purchase.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Check if the plan purchase is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'activated' &&
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if the plan purchase is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
               ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Activate the plan purchase.
     */
    public function activate(): void
    {
        if (!$this->plan) {
            \Illuminate\Support\Facades\Log::error('Cannot activate plan purchase: plan not found', [
                'plan_purchase_id' => $this->id,
                'plan_id' => $this->plan_id
            ]);
            throw new \Exception('Plan not found for plan purchase');
        }

        $validityDays = $this->plan->getValidityDays(); // Use the plan's validity days

        \Illuminate\Support\Facades\Log::info('Activating plan purchase', [
            'plan_purchase_id' => $this->id,
            'plan_id' => $this->plan_id,
            'user_id' => $this->user_id,
            'validity_days' => $validityDays
        ]);

        $this->update([
            'status' => 'activated',
            'activated_at' => now(),
            'expires_at' => now()->addDays($validityDays),
            'used_contacts' => 0,
            'used_featured_listings' => 0,
        ]);

        \Illuminate\Support\Facades\Log::info('Plan purchase activated successfully', [
            'plan_purchase_id' => $this->id,
            'status' => $this->status,
            'expires_at' => $this->expires_at
        ]);
    }

    /**
     * Generate HTML invoice for the plan purchase.
     */
    public function generateInvoiceHtml(): string
    {
        return view('invoices.plan-purchase', ['planPurchase' => $this])->render();
    }

    /**
     * Deactivate all active plan purchases for a user.
     */
    public static function deactivateActivePlansForUser(int $userId, int $excludeId = null): int
    {
        $activePurchases = self::where('user_id', $userId)
            ->where('status', 'activated')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->when($excludeId, function($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })
            ->get();

        $count = $activePurchases->count();

        foreach ($activePurchases as $purchase) {
            $purchase->update(['status' => 'deactivated']);
            \Illuminate\Support\Facades\Log::info('Plan purchase deactivated', [
                'plan_purchase_id' => $purchase->id,
                'user_id' => $userId,
                'plan_id' => $purchase->plan_id
            ]);
        }

        return $count;
    }

    /**
     * Deactivate active paid plans for a user (plans with price > 0).
     */
    public static function deactivateActivePaidPlansForUser(int $userId): void
    {
        $activePaidPurchases = self::where('user_id', $userId)
            ->where('status', 'activated')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereHas('plan', function($q) {
                $q->where('price', '>', 0);
            })
            ->get();

        foreach ($activePaidPurchases as $purchase) {
            $purchase->update(['status' => 'deactivated']);
            \Illuminate\Support\Facades\Log::info('Paid plan purchase deactivated', [
                'plan_purchase_id' => $purchase->id,
                'user_id' => $userId,
                'plan_id' => $purchase->plan_id
            ]);
        }
    }

}
