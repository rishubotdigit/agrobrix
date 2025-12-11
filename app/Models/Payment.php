<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'user_id',
        'property_id',
        'plan_purchase_id',
        'status',
        'order_id',
        'payment_id',
        'signature',
        'transaction_id',
        'transaction_submitted_at',
        'transaction_submitted_ip',
        'transaction_submitted_user_agent',
        'type',
        'metadata',
        'gateway',
        'gateway_response',
        'approval_status',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'gateway_response' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the property that the payment is for.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the plan purchase that the payment is for.
     */
    public function planPurchase(): BelongsTo
    {
        return $this->belongsTo(PlanPurchase::class);
    }

    /**
     * Get the admin user who approved/rejected the payment.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return in_array($this->status, ['pending', 'pending_approval']);
    }

    /**
     * Check if transaction ID is submitted
     */
    public function hasTransactionId(): bool
    {
        return !empty($this->transaction_id);
    }

    /**
     * Submit transaction ID for UPI static payments
     */
    public function submitTransactionId(string $transactionId): bool
    {
        if ($this->gateway !== 'upi_static') {
            return false;
        }

        if ($this->status !== 'pending') {
            return false;
        }

        $this->transaction_id = $transactionId;
        $this->status = 'pending_approval';
        $this->approval_status = 'pending';
        $this->payment_id = $transactionId; // Use transaction ID as payment ID for consistency

        return $this->save();
    }

    /**
     * Check if payment is pending admin approval
     */
    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval' && $this->approval_status === 'pending';
    }

    /**
     * Check if payment is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if payment is rejected
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Approve the payment
     */
    public function approve(int $adminId, string $notes = null): bool
    {
        if (!$this->isPendingApproval()) {
            return false;
        }

        $this->approval_status = 'approved';
        $this->status = 'completed';
        $this->approved_by = $adminId;
        $this->approved_at = now();
        $this->admin_notes = $notes;

        return $this->save();
    }

    /**
     * Reject the payment
     */
    public function reject(int $adminId, string $notes = null): bool
    {
        if (!$this->isPendingApproval()) {
            return false;
        }

        $this->approval_status = 'rejected';
        $this->approved_by = $adminId;
        $this->approved_at = now();
        $this->admin_notes = $notes;

        return $this->save();
    }
}
