<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
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
            $admin->notify(new PlanPurchaseApprovalNeeded($event->planPurchase));
        }
    }
}
