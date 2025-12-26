<?php

namespace App\Listeners;

use App\Events\PropertyApproved;
use App\Mail\NotifyAdminPropertyApproved;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Mail;

class NotifyAdminsOfPropertyApproved
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
        $admins = User::where('role', 'admin')->where('id', '!=', $event->admin_id)->get();

        foreach ($admins as $admin) {
            // Check if notification already exists for this admin and property approval
            $exists = Notification::where('type', 'property_approved_admin')
                ->where('user_id', $admin->id)
                ->where('data->property_id', $event->property->id)
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'property_approved_admin',
                    'message' => 'Property "' . $event->property->title . '" has been approved and is now live.',
                    'data' => [
                        'property_id' => $event->property->id,
                    ],
                ]);
            }
        }

        // Send email notification to admins if enabled
        if (Setting::get('admin_property_approved_notification_enabled', '1') === '1') {
            $admins = User::where('role', 'admin')->where('id', '!=', $event->admin_id)->get();
            $recipients = $admins->map(function ($admin) use ($event) {
                return [
                    'email' => $admin->email,
                    'user_id' => $admin->id,
                    'model_type' => 'App\Models\Property',
                    'model_id' => $event->property->id,
                ];
            })->toArray();

            \App\Jobs\SendBulkEmailJob::dispatch(
                new NotifyAdminPropertyApproved($event->property),
                $recipients,
                'notify_admin_property_approved'
            );
        }
    }
}