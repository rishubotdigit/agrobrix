<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Notification;
use App\Models\EmailLog;
use App\Models\User;
use App\Mail\WelcomeUser;
use App\Mail\NotifyAdminNewUser;
use App\Models\Setting;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CreateNotificationForUserRegistered
{
    use DynamicSmtpTrait;

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
            $start = microtime(true);
            DynamicSmtpTrait::loadSmtpSettings();
            try {
                Mail::to($event->user->email)->send(new WelcomeUser($event->user));
                $duration = microtime(true) - $start;
                Log::info('Welcome email sent successfully', ['user_id' => $event->user->id, 'duration' => $duration]);
                \App\Models\EmailLog::create([
                    'email_type' => 'welcome_user',
                    'recipient_email' => $event->user->email,
                    'user_id' => $event->user->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $event->user->id,
                    'status' => 'sent',
                ]);
            } catch (\Exception $e) {
                $duration = microtime(true) - $start;
                Log::error('Failed to send welcome email to user', [
                    'user_id' => $event->user->id,
                    'user_email' => $event->user->email,
                    'duration' => $duration,
                    'error' => $e->getMessage(),
                ]);
                \App\Models\EmailLog::create([
                    'email_type' => 'welcome_user',
                    'recipient_email' => $event->user->email,
                    'user_id' => $event->user->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $event->user->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        // Send notification email to admins if enabled and admins exist
        if (Setting::get('admin_new_user_notification_enabled', '1') === '1' && User::where('role', 'admin')->exists()) {
            $start = microtime(true);
            DynamicSmtpTrait::loadSmtpSettings();
            try {
                Mail::send(new NotifyAdminNewUser($event->user));
                $duration = microtime(true) - $start;
                Log::info('Admin notification email sent successfully', ['user_id' => $event->user->id, 'duration' => $duration]);
                // For admin emails, recipient is multiple, so perhaps log without recipient or with admin emails
                // For simplicity, log as queued to admins
                \App\Models\EmailLog::create([
                    'email_type' => 'notify_admin_new_user',
                    'recipient_email' => 'admins', // or get admin emails
                    'user_id' => $event->user->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $event->user->id,
                    'status' => 'sent',
                ]);
            } catch (\Exception $e) {
                $duration = microtime(true) - $start;
                Log::error('Failed to send new user notification email to admins', [
                    'user_id' => $event->user->id,
                    'duration' => $duration,
                    'error' => $e->getMessage(),
                ]);
                \App\Models\EmailLog::create([
                    'email_type' => 'notify_admin_new_user',
                    'recipient_email' => 'admins',
                    'user_id' => $event->user->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $event->user->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }
}