<?php

namespace App\Mail;

use App\Models\User;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NotifyAdminNewUser extends Mailable
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
            subject: 'New User Registration Notification',
            to: User::where('role', 'admin')->pluck('email')->toArray(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.notify-admin-new-user',
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
