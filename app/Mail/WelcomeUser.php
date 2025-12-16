<?php

namespace App\Mail;

use App\Models\User;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeUser extends Mailable
{
    use DynamicSmtpTrait;

    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name') . ' - Account Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.welcome-user',
            with: [
                'user' => $this->user,
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
