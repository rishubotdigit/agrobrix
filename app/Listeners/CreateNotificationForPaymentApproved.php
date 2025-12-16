<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Models\Notification;

class CreateNotificationForPaymentApproved
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
    public function handle(PaymentApproved $event): void
    {
        // Check if notification already exists for this payment approval
        $exists = Notification::where('type', 'payment_approved')
            ->where('user_id', $event->payment->user_id)
            ->where('data->payment_id', $event->payment->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'user_id' => $event->payment->user_id,
                'type' => 'payment_approved',
                'message' => 'Your ' . $event->payment->type . ' payment of ₹' . $event->payment->amount . ' has been approved.',
                'data' => [
                    'payment_id' => $event->payment->id,
                ],
            ]);
        }
    }
}
