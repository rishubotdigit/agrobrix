<?php

namespace App\Listeners;

use App\Events\PropertySubmittedForApproval;
use App\Mail\NotifyAdminPropertySubmitted;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Mail;

class CreateNotificationForPropertySubmittedForApproval
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
    public function handle(PropertySubmittedForApproval $event): void
    {
        // Check if notification already exists for this property
        $exists = Notification::where('type', 'property_submitted')
            ->where('data->property_id', $event->property->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'property_submitted',
                'message' => 'A new property "' . $event->property->title . '" in ' . $event->property->city->name . ', ' . $event->property->city->district->state->name . ' has been submitted for approval by ' . $event->property->owner->name . '.',
                'data' => [
                    'property_id' => $event->property->id,
                ],
            ]);
        }

        // Send email notification to admins if enabled
        if (Setting::get('admin_property_submitted_notification_enabled', '1') === '1') {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                DynamicSmtpTrait::loadSmtpSettings();
                try {
                    Mail::to($admin->email)->send(new NotifyAdminPropertySubmitted($event->property));
                    EmailLog::create([
                        'email_type' => 'notify_admin_property_submitted',
                        'recipient_email' => $admin->email,
                        'user_id' => $event->property->user_id,
                        'model_type' => 'App\Models\Property',
                        'model_id' => $event->property->id,
                        'status' => 'sent',
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin property submitted email: ' . $e->getMessage());
                    EmailLog::create([
                        'email_type' => 'notify_admin_property_submitted',
                        'recipient_email' => $admin->email,
                        'user_id' => $event->property->user_id,
                        'model_type' => 'App\Models\Property',
                        'model_id' => $event->property->id,
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}