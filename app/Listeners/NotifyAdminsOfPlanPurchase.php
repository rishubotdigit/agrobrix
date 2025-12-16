<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
use App\Models\Notification;
use App\Models\User;
use App\Models\EmailLog;
use App\Notifications\PlanPurchaseApprovalNeeded;

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
            $exists = Notification::where('notifiable_type', User::class)
                ->where('notifiable_id', $admin->id)
                ->where('type', PlanPurchaseApprovalNeeded::class)
                ->where('data->plan_purchase_id', $event->planPurchase->id)
                ->exists();

            if (!$exists) {
                try {
                    $admin->notify(new PlanPurchaseApprovalNeeded($event->planPurchase));
                    EmailLog::create([
                        'email_type' => 'notify_admin_plan_purchase_approval_needed',
                        'recipient_email' => $admin->email,
                        'user_id' => $event->planPurchase->user_id,
                        'model_type' => 'App\Models\PlanPurchase',
                        'model_id' => $event->planPurchase->id,
                        'status' => 'sent',
                    ]);
                } catch (\Exception $e) {
                    EmailLog::create([
                        'email_type' => 'notify_admin_plan_purchase_approval_needed',
                        'recipient_email' => $admin->email,
                        'user_id' => $event->planPurchase->user_id,
                        'model_type' => 'App\Models\PlanPurchase',
                        'model_id' => $event->planPurchase->id,
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
