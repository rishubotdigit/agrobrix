<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\PlanPurchaseApprovalNeeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminsOfPlanPurchase implements ShouldQueue
{
    use InteractsWithQueue;

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
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            // Check if notification already exists for this admin and plan purchase
            $exists = Notification::where('notifiable_type', User::class)
                ->where('notifiable_id', $admin->id)
                ->where('type', PlanPurchaseApprovalNeeded::class)
                ->where('data->plan_purchase_id', $event->planPurchase->id)
                ->exists();

            if (!$exists) {
                $admin->notify(new PlanPurchaseApprovalNeeded($event->planPurchase));
            }
        }
    }
}
