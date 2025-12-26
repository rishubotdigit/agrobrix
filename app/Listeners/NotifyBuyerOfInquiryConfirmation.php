<?php

namespace App\Listeners;

use App\Events\InquiryCreated;
use App\Jobs\SendEmailJob;
use App\Mail\InquiryConfirmation;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Traits\EmailQueueTrait;
use Illuminate\Support\Facades\Log;

class NotifyBuyerOfInquiryConfirmation
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
    public function handle(InquiryCreated $event): void
    {
        Log::info('NotifyBuyerOfInquiryConfirmation event handled', ['lead_id' => $event->lead->id]);

        $lead = $event->lead;
        $property = $lead->property;

        // Send email to buyer
        $inquiryData = [
            'buyer_name' => $lead->buyer_name,
            'buyer_email' => $lead->buyer_email,
            'buyer_phone' => $lead->buyer_phone,
            'buyer_type' => $lead->buyer_type,
            'additional_message' => $lead->additional_message,
        ];

        $this->sendOrQueueEmail(
            new InquiryConfirmation($property, $inquiryData),
            $lead->buyer_email,
            null, // No user ID for guest buyers
            null,
            $lead->id,
            'inquiry_confirmation'
        );

        // Check if buyer is a registered user for in-app notification
        $buyer = User::where('email', $lead->buyer_email)->first();
        if ($buyer) {
            // Create in-app notification
            Notification::create([
                'user_id' => $buyer->id,
                'type' => 'inquiry_confirmation',
                'message' => 'Your inquiry for property "' . $property->title . '" has been submitted successfully.',
                'data' => [
                    'lead_id' => $lead->id,
                    'property_id' => $property->id,
                ],
            ]);
        }
    }
}