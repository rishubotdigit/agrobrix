<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\InvoiceEmail;
use App\Models\Setting;
use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmail
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
        // Skip sending invoice for Basic plan (free plan)
        if ($event->payment->planPurchase && $event->payment->planPurchase->plan->price == 0) {
            return;
        }

        // Send invoice email to user if enabled
        if (Setting::get('invoice_email_enabled', '1') === '1') {
            DynamicSmtpTrait::loadSmtpSettings();
            try {
                Mail::to($event->payment->user->email)->send(new InvoiceEmail($event->payment));
                EmailLog::create([
                    'email_type' => 'invoice',
                    'recipient_email' => $event->payment->user->email,
                    'user_id' => $event->payment->user_id,
                    'status' => 'sent',
                ]);
            } catch (\Exception $e) {
                // Log error but don't fail the job
                \Log::error('Failed to send invoice email: ' . $e->getMessage());
                EmailLog::create([
                    'email_type' => 'invoice',
                    'recipient_email' => $event->payment->user->email,
                    'user_id' => $event->payment->user_id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }
}