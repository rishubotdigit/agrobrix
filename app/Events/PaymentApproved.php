<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentApproved
{
    use Dispatchable, SerializesModels;

    public Payment $payment;
    public int $admin_id;

    /**
     * Create a new event instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->admin_id = $payment->approved_by;
    }
}
