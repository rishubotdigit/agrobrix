<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
use App\Models\Notification;

class CreateNotificationForPlanPurchaseCreated
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
    public function handle(PlanPurchaseCreated $event): void
    {
        // Check if notification already exists for this plan purchase
        $exists = Notification::where('type', 'plan_purchase')
            ->where('data->plan_purchase_id', $event->planPurchase->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'plan_purchase',
                'message' => 'A new plan "' . ($event->planPurchase->plan->name ?? 'Unknown Plan') . '" has been purchased by ' . $event->planPurchase->user->name . '.',
                'data' => [
                    'plan_purchase_id' => $event->planPurchase->id,
                ],
            ]);
        }
    }
}