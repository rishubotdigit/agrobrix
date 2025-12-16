<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\InvoiceEmail;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmail implements ShouldQueue
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
        // Send invoice email to user if enabled
        if (Setting::get('invoice_email_enabled', '1') === '1') {
            try {
                Mail::to($event->payment->user->email)->send(new InvoiceEmail($event->payment));
            } catch (\Exception $e) {
                // Log error but don't fail the job
                \Log::error('Failed to send invoice email: ' . $e->getMessage());
            }
        }
    }
}