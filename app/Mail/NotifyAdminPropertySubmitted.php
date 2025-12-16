<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\User;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NotifyAdminPropertySubmitted extends Mailable
{
    use DynamicSmtpTrait;

    public Property $property;

    /**
     * Create a new message instance.
     */
    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Property Submitted for Approval - ' . config('app.name'),
            to: User::where('role', 'admin')->pluck('email')->toArray(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.notify-admin-property-submitted',
            with: [
                'property' => $this->property,
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