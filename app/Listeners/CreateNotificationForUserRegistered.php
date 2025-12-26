<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\SendBulkEmailJob;
use App\Models\Notification;
use App\Models\User;
use App\Mail\WelcomeUser;
use App\Mail\NotifyAdminNewUser;
use App\Models\Setting;
use App\Traits\EmailQueueTrait;
use Illuminate\Support\Facades\Log;

class CreateNotificationForUserRegistered
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
    public function handle(UserRegistered $event): void
    {
        Log::info('UserRegistered event handled', ['user_id' => $event->user->id, 'email' => $event->user->email]);

        // Check if notification already exists for this user
        $exists = Notification::where('type', 'user_registered')
            ->where('data->user_id', $event->user->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'user_registered',
                'message' => 'A new user ' . $event->user->name . ' (' . $event->user->email . ') has registered as ' . $event->user->role . '.',
                'data' => [
                    'user_id' => $event->user->id,
                ],
            ]);
        }

        // Send welcome email to user if enabled
        if (Setting::get('user_registration_welcome_email_enabled', '1') === '1') {
            Log::info('Sending welcome email to user', ['user_id' => $event->user->id, 'role' => $event->user->role]);
            $this->sendOrQueueEmail(
                new WelcomeUser($event->user),
                $event->user->email,
                $event->user->id,
                'App\Models\User',
                $event->user->id,
                'welcome_user'
            );
        }

        // Send notification email to admins if enabled and admins exist
        if (Setting::get('admin_new_user_notification_enabled', '1') === '1' && User::where('role', 'admin')->exists() && Setting::get('queue_mode', 'disabled') === 'enabled') {
            $admins = User::where('role', 'admin')->get();
            $recipients = $admins->map(function ($admin) use ($event) {
                return [
                    'email' => $admin->email,
                    'user_id' => $admin->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $event->user->id,
                ];
            })->toArray();

            SendBulkEmailJob::dispatch(
                new NotifyAdminNewUser($event->user),
                $recipients,
                'notify_admin_new_user'
            );
        }
    }
}