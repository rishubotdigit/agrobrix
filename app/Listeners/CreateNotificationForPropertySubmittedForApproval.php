<?php

namespace App\Listeners;

use App\Events\PropertySubmittedForApproval;
use App\Models\Notification;

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
    }
}