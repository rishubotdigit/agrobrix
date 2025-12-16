<?php

namespace App\Listeners;

use App\Events\PropertyRejected;
use App\Mail\PropertyRejected as PropertyRejectedMail;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateNotificationForPropertyRejected implements ShouldQueue
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
                try {
                    Mail::to($user->email)->send(new PropertyRejectedMail($event->property));
                    Log::info('Property rejection email sent to user: ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send property rejection email to user', [
                        'property_id' => $event->property->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage(),
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