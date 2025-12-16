<?php

namespace App\Listeners;

use App\Events\PropertyRejected;
use App\Mail\PropertyRejected as PropertyRejectedMail;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateNotificationForPropertyRejected
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
    public function handle(PropertyRejected $event): void
    {
        Log::info('CreateNotificationForPropertyRejected triggered for property ID: ' . $event->property->id);

        $userId = $event->property->agent_id ?? $event->property->owner_id;
        Log::info('User ID for notification: ' . $userId);

        // Check if notification already exists for this property rejection
        $exists = Notification::where('type', 'property_rejected')
            ->where('user_id', $userId)
            ->where('data->property_id', $event->property->id)
            ->exists();

        Log::info('Notification exists check: ' . ($exists ? 'true' : 'false'));

        if (!$exists) {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => 'property_rejected',
                'message' => 'Your property "' . $event->property->title . '" has been rejected.',
                'data' => [
                    'property_id' => $event->property->id,
                ],
            ]);
            Log::info('Notification created with ID: ' . $notification->id);
        }

        // Send rejection email to the user if enabled
        if (Setting::get('property_rejection_email_enabled', '1') === '1') {
            $user = $event->property->agent ?? $event->property->owner;
            if ($user && $user->email) {
                DynamicSmtpTrait::loadSmtpSettings();
                try {
                    Mail::to($user->email)->send(new PropertyRejectedMail($event->property));
                    Log::info('Property rejection email sent to user: ' . $user->email);
                    EmailLog::create([
                        'email_type' => 'property_rejected',
                        'recipient_email' => $user->email,
                        'user_id' => $userId,
                        'model_type' => 'App\Models\Property',
                        'model_id' => $event->property->id,
                        'status' => 'sent',
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send property rejection email to user', [
                        'property_id' => $event->property->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                    EmailLog::create([
                        'email_type' => 'property_rejected',
                        'recipient_email' => $user->email,
                        'user_id' => $userId,
                        'model_type' => 'App\Models\Property',
                        'model_id' => $event->property->id,
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('No user email found for property rejection notification', [
                    'property_id' => $event->property->id,
                    'user_id' => $userId,
                ]);
            }
        }
    }
}