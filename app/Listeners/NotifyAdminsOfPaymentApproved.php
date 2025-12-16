<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\NotifyAdminPaymentApproved;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminsOfPaymentApproved implements ShouldQueue
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
    public function handle(PaymentApproved $event): void
    {
        $admins = User::where('role', 'admin')->where('id', '!=', $event->admin_id)->get();

        foreach ($admins as $admin) {
            // Check if notification already exists for this admin and payment approval
            $exists = Notification::where('type', 'payment_approved_admin')
                ->where('user_id', $admin->id)
                ->where('data->payment_id', $event->payment->id)
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'payment_approved_admin',
                    'message' => 'Payment of ₹' . $event->payment->amount . ' for ' . $event->payment->type . ' has been approved.',
                    'data' => [
                        'payment_id' => $event->payment->id,
                    ],
                ]);
            }
        }

        // Send email notification to admins if enabled
        if (Setting::get('admin_payment_approved_notification_enabled', '1') === '1') {
            $admins = User::where('role', 'admin')->where('id', '!=', $event->admin_id)->get();
            foreach ($admins as $admin) {
                try {
                    Mail::to($admin->email)->send(new NotifyAdminPaymentApproved($event->payment));
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin payment approved email: ' . $e->getMessage());
                }
            }
        }
    }
}