<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\InvoiceEmail;
use App\Models\Setting;
use App\Traits\EmailQueueTrait;

class SendInvoiceEmail
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
        // Skip sending invoice for Basic plan (free plan)
        if ($event->payment->planPurchase && $event->payment->planPurchase->plan->price == 0) {
            return;
        }

        // Send invoice email to user if enabled
        if (Setting::get('invoice_email_enabled', '1') === '1') {
            $this->sendOrQueueEmail(
                new InvoiceEmail($event->payment),
                $event->payment->user->email,
                $event->payment->user_id,
                'App\Models\Payment',
                $event->payment->id,
                'invoice'
            );
        }
    }
}