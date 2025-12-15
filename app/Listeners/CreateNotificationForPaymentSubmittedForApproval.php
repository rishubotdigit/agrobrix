<?php

namespace App\Listeners;

use App\Events\PaymentSubmittedForApproval;
use App\Models\Notification;

class CreateNotificationForPaymentSubmittedForApproval
{
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
    public function handle(PaymentSubmittedForApproval $event): void
    {
        // Check if notification already exists for this payment
        $exists = Notification::where('type', 'payment_submitted')
            ->where('data->payment_id', $event->payment->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'payment_submitted',
                'message' => 'A ' . $event->payment->type . ' payment of ₹' . $event->payment->amount . ' has been submitted for approval by ' . $event->payment->user->name . '.',
                'data' => [
                    'payment_id' => $event->payment->id,
                ],
            ]);
        }
    }
}