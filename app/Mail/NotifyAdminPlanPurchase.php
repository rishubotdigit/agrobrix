<?php

namespace App\Mail;

use App\Models\PlanPurchase;
use App\Models\User;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAdminPlanPurchase extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, DynamicSmtpTrait;

    public PlanPurchase $planPurchase;

    /**
     * Create a new message instance.
     */
    public function __construct(PlanPurchase $planPurchase)
    {
        $this->planPurchase = $planPurchase;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Plan Purchase Notification',
            to: User::where('role', 'admin')->pluck('email')->toArray(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.notify-admin-plan-purchase',
            with: [
                'planPurchase' => $this->planPurchase,
                'invoiceHtml' => $this->planPurchase->generateInvoiceHtml(),
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