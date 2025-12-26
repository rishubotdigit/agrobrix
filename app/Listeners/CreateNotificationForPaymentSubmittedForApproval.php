<?php

namespace App\Listeners;

use App\Events\PaymentSubmittedForApproval;
use App\Mail\NotifyAdminPaymentSubmitted;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Mail;

class CreateNotificationForPaymentSubmittedForApproval
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
    public function handle(PaymentSubmittedForApproval $event): void
    {
        // Check if notification already exists for this payment
        $exists = Notification::where('type', 'payment_submitted')
            ->where('data->payment_id', $event->payment->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'payment_submitted',
                'message' => 'A ' . $event->payment->type . ' payment of ₹' . $event->payment->amount . ' has been submitted for approval by ' . $event->payment->user->name . '.',
                'data' => [
                    'payment_id' => $event->payment->id,
                ],
            ]);
        }

        // Send email notification to admins if enabled
        if (Setting::get('admin_payment_submitted_notification_enabled', '1') === '1') {
            $admins = User::where('role', 'admin')->get();
            $recipients = $admins->map(function ($admin) use ($event) {
                return [
                    'email' => $admin->email,
                    'user_id' => $admin->id,
                    'model_type' => 'App\Models\Payment',
                    'model_id' => $event->payment->id,
                ];
            })->toArray();

            \App\Jobs\SendBulkEmailJob::dispatch(
                new NotifyAdminPaymentSubmitted($event->payment),
                $recipients,
                'notify_admin_payment_submitted'
            );
        }
    }
}