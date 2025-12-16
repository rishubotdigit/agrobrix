<?php

namespace App\Listeners;

use App\Events\PlanPurchaseCreated;
use App\Mail\NotifyAdminPlanPurchase;
use App\Mail\PlanPurchaseConfirmation;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        // Send confirmation email to user if enabled
        if (Setting::get('user_plan_purchase_confirmation_enabled', '1') === '1') {
            try {
                Mail::to($event->planPurchase->user->email)->send(new PlanPurchaseConfirmation($event->planPurchase));
            } catch (\Exception $e) {
                Log::error('Failed to send plan purchase confirmation email to user', [
                    'plan_purchase_id' => $event->planPurchase->id,
                    'user_email' => $event->planPurchase->user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Send notification email to admins if enabled
        if (Setting::get('admin_plan_purchase_notification_enabled', '1') === '1') {
            try {
                Mail::send(new NotifyAdminPlanPurchase($event->planPurchase));
            } catch (\Exception $e) {
                Log::error('Failed to send plan purchase notification email to admins', [
                    'plan_purchase_id' => $event->planPurchase->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}