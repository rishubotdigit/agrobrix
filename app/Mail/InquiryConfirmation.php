<?php

namespace App\Mail;

use App\Models\Property;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InquiryConfirmation extends Mailable
{
    use DynamicSmtpTrait;

    public Property $property;
    public array $inquiryData;

    /**
     * Create a new message instance.
     */
    public function __construct(Property $property, array $inquiryData)
    {
        $this->property = $property;
        $this->inquiryData = $inquiryData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Inquiry Confirmation - ' . config('app.name'),
            to: $this->inquiryData['buyer_email'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.inquiry-confirmation',
            with: [
                'property' => $this->property,
                'inquiryData' => $this->inquiryData,
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