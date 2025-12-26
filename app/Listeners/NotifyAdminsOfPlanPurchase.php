<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
use App\Jobs\SendBulkEmailJob;
use App\Mail\NotifyAdminPlanPurchase;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;

class NotifyAdminsOfPlanPurchase
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
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            // Check if notification already exists for this admin and plan purchase
            $exists = Notification::where('type', 'plan_purchase_approval_needed')
                ->where('user_id', $admin->id)
                ->where('data->plan_purchase_id', $event->planPurchase->id)
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'plan_purchase_approval_needed',
                    'message' => 'New plan purchase requires approval: ' . $event->planPurchase->plan->name . ' by ' . $event->planPurchase->user->name,
                    'data' => [
                        'plan_purchase_id' => $event->planPurchase->id,
                    ],
                ]);
            }
        }

        // Send email notification to admins if enabled
        if (Setting::get('admin_plan_purchase_notification_enabled', '1') === '1') {
            $admins = User::where('role', 'admin')->get();
            $recipients = $admins->map(function ($admin) use ($event) {
                return [
                    'email' => $admin->email,
                    'user_id' => $admin->id,
                    'model_type' => 'App\Models\PlanPurchase',
                    'model_id' => $event->planPurchase->id,
                ];
            })->toArray();

            SendBulkEmailJob::dispatch(
                new NotifyAdminPlanPurchase($event->planPurchase),
                $recipients,
                'notify_admin_plan_purchase'
            );
        }
    }
}
