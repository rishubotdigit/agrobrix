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
               $this->expires_at &&
               $this->expires_at->isFuture();
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

        $validityDays = $this->plan->validity_period_days ?? 30; // Default to 30 days if null

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

}
