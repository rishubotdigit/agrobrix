<?php

namespace App\Listeners;

use App\Events\PropertySubmittedForApproval;
use App\Mail\NotifyAdminPropertySubmitted;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
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
                'message' => 'A new property "' . $event->property->title . '" in ' . $event->property->city . ', ' . $event->property->state . ' has been submitted for approval by ' . $event->property->owner->name . '.',
                'data' => [
                    'property_id' => $event->property->id,
                ],
            ]);
        }

        // Send email notification to admins if enabled
        if (Setting::get('admin_property_submitted_notification_enabled', '1') === '1') {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                try {
                    Mail::to($admin->email)->send(new NotifyAdminPropertySubmitted($event->property));
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin property submitted email: ' . $e->getMessage());
                }
            }
        }
    }
}