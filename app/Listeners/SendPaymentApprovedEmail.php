<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\PaymentApproved as PaymentApprovedMail;
use App\Models\Setting;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Mail;

class SendPaymentApprovedEmail
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
    public function handle(PaymentApproved $event): void
    {
        // Send payment approved email to user if enabled
        if (Setting::get('payment_approved_email_enabled', '1') === '1') {
            DynamicSmtpTrait::loadSmtpSettings();
            try {
                Mail::to($event->payment->user->email)->send(new PaymentApprovedMail($event->payment));
                EmailLog::create([
                    'email_type' => 'payment_approved',
                    'recipient_email' => $event->payment->user->email,
                    'user_id' => $event->payment->user_id,
                    'model_type' => 'App\Models\Payment',
                    'model_id' => $event->payment->id,
                    'status' => 'sent',
                ]);
            } catch (\Exception $e) {
                // Log error but don't fail the job
                \Log::error('Failed to send payment approved email: ' . $e->getMessage());
                EmailLog::create([
                    'email_type' => 'payment_approved',
                    'recipient_email' => $event->payment->user->email,
                    'user_id' => $event->payment->user_id,
                    'model_type' => 'App\Models\Payment',
                    'model_id' => $event->payment->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }
}