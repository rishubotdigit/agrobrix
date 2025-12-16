<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAdminPaymentSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, DynamicSmtpTrait;

    public Payment $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Payment Submitted for Approval - ' . config('app.name'),
            to: User::where('role', 'admin')->pluck('email')->toArray(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.notify-admin-payment-submitted',
            with: [
                'payment' => $this->payment,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}