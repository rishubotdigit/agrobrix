<?php

namespace App\Listeners;

use App\Events\PaymentSubmittedForApproval;
use App\Mail\NotifyAdminPaymentSubmitted;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
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
            foreach ($admins as $admin) {
                try {
                    Mail::to($admin->email)->send(new NotifyAdminPaymentSubmitted($event->payment));
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin payment submitted email: ' . $e->getMessage());
                }
            }
        }
    }
}