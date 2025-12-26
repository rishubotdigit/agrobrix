<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
use App\Jobs\SendBulkEmailJob;
use App\Mail\NotifyAdminPlanPurchase;
use App\Mail\PlanPurchaseConfirmation;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Traits\EmailQueueTrait;

class CreateNotificationForPlanPurchaseCreated
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

        // Create notification for the purchaser (buyer)
        $buyerExists = Notification::where('type', 'plan_purchase_buyer')
            ->where('user_id', $event->planPurchase->user_id)
            ->where('data->plan_purchase_id', $event->planPurchase->id)
            ->exists();

        if (!$buyerExists) {
            Notification::create([
                'user_id' => $event->planPurchase->user_id,
                'type' => 'plan_purchase_buyer',
                'message' => 'You have successfully purchased the plan "' . ($event->planPurchase->plan->name ?? 'Unknown Plan') . '".',
                'data' => [
                    'plan_purchase_id' => $event->planPurchase->id,
                ],
            ]);
        }

        // Send confirmation email to user if enabled
        if (Setting::get('user_plan_purchase_confirmation_enabled', '1') === '1') {
            $this->sendOrQueueEmail(
                new PlanPurchaseConfirmation($event->planPurchase),
                $event->planPurchase->user->email,
                $event->planPurchase->user_id,
                'App\Models\PlanPurchase',
                $event->planPurchase->id,
                'plan_purchase_confirmation'
            );
        }

        // Send notification email to admins if enabled
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