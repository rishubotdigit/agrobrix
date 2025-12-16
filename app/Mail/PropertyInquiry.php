<?php

namespace App\Mail;

use App\Models\Lead;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PropertyInquiry extends Mailable
{
    use DynamicSmtpTrait;

    public Lead $lead;

    /**
     * Create a new message instance.
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $recipient = $this->lead->agent ?? $this->lead->property->owner;

        return new Envelope(
            subject: 'New Property Inquiry - ' . config('app.name'),
            to: $recipient->email,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.property-inquiry',
            with: [
                'lead' => $this->lead,
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