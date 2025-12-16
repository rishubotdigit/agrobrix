<?php

namespace App\Mail;

use App\Models\PlanPurchase;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PlanExpirationWarning extends Mailable
{
    use DynamicSmtpTrait;

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
            subject: 'Plan Expiration Warning - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.plan-expiration-warning',
            with: [
                'planPurchase' => $this->planPurchase,
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