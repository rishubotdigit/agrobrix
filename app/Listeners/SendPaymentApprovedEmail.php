<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\PaymentApproved as PaymentApprovedMail;
use App\Models\Setting;
use App\Traits\EmailQueueTrait;

class SendPaymentApprovedEmail
{
    use EmailQueueTrait;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentApproved $event): void
    {
        // Send payment approved email to user if enabled
        if (Setting::get('payment_approved_email_enabled', '1') === '1') {
            $this->sendOrQueueEmail(
                new PaymentApprovedMail($event->payment),
                $event->payment->user->email,
                $event->payment->user_id,
                'App\Models\Payment',
                $event->payment->id,
                'payment_approved'
            );
        }
    }
}