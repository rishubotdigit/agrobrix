<?php

namespace App\Listeners;

use App\Events\PropertyApproved;
use App\Mail\PropertyApproved as PropertyApprovedMail;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateNotificationForPropertyApproved
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
    public function handle(PropertyApproved $event): void
    {
        Log::info('CreateNotificationForPropertyApproved triggered for property ID: ' . $event->property->id);

        $userId = $event->property->agent_id ?? $event->property->owner_id;
        Log::info('User ID for notification: ' . $userId);

        // Check if notification already exists for this property approval
        $exists = Notification::where('type', 'property_approved')
            ->where('user_id', $userId)
            ->where('data->property_id', $event->property->id)
            ->exists();

        Log::info('Notification exists check: ' . ($exists ? 'true' : 'false'));

        if (!$exists) {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => 'property_approved',
                'message' => 'Your property "' . $event->property->title . '" has been approved and is now live.',
                'data' => [
                    'property_id' => $event->property->id,
                ],
            ]);
            Log::info('Notification created with ID: ' . $notification->id);
        }

        // Send approval email to the user if enabled
        if (Setting::get('property_approval_email_enabled', '1') === '1') {
            $user = $event->property->agent ?? $event->property->owner;
            if ($user && $user->email) {
                DynamicSmtpTrait::loadSmtpSettings();
                try {
                    Mail::to($user->email)->send(new PropertyApprovedMail($event->property));
                    Log::info('Property approval email sent to user: ' . $user->email);
                    EmailLog::create([
                        'email_type' => 'property_approved',
                        'recipient_email' => $user->email,
                        'user_id' => $userId,
                        'model_type' => 'App\Models\Property',
                        'model_id' => $event->property->id,
                        'status' => 'sent',
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send property approval email to user', [
                        'property_id' => $event->property->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                    EmailLog::create([
                        'email_type' => 'property_approved',
                        'recipient_email' => $user->email,
                        'user_id' => $userId,
                        'model_type' => 'App\Models\Property',
                        'model_id' => $event->property->id,
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('No user email found for property approval notification', [
                    'property_id' => $event->property->id,
                    'user_id' => $userId,
                ]);
            }
        }
    }
}
