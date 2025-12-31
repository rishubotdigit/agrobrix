<?php

namespace App\Listeners;

use App\Events\InquiryCreated;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotifyOwnerOfNewInquiry
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
    public function handle(InquiryCreated $event): void
    {
        Log::info('NotifyOwnerOfNewInquiry event handled', ['lead_id' => $event->lead->id]);

        $lead = $event->lead;
        $property = $lead->property;

        // Determine who to notify: agent if assigned, otherwise owner
        $notifyUserId = $lead->agent_id ?? $property->owner_id;

        if ($notifyUserId) {
            $notifyUser = \App\Models\User::find($notifyUserId);
            Log::info('NotifyOwnerOfNewInquiry: notifying user', [
                'notify_user_id' => $notifyUserId,
                'notify_user_role' => $notifyUser ? $notifyUser->role : 'unknown',
                'property_owner_id' => $property->owner_id,
                'property_owner_role' => $property->owner ? $property->owner->role : 'unknown',
                'lead_id' => $lead->id
            ]);

            Notification::create([
                'user_id' => $notifyUserId,
                'type' => 'new_inquiry',
                'message' => 'New inquiry received for your property "' . $property->title . '" from ' . $lead->buyer_name . '.',
                'data' => [
                    'lead_id' => $lead->id,
                    'property_id' => $property->id,
                    'buyer_name' => $lead->buyer_name,
                    'buyer_email' => $lead->buyer_email,
                ],
            ]);
        }
    }
}