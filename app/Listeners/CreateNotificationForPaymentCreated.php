<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use App\Models\Notification;

class CreateNotificationForPaymentCreated
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
    public function handle(PaymentCreated $event): void
    {
        // Check if notification already exists for this payment
        $exists = Notification::where('type', 'payment_created')
            ->where('data->payment_id', $event->payment->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'payment_created',
                'message' => 'A new ' . $event->payment->type . ' payment of ₹' . $event->payment->amount . ' has been created by ' . $event->payment->user->name . '.',
                'data' => [
                    'payment_id' => $event->payment->id,
                ],
            ]);
        }
    }
}