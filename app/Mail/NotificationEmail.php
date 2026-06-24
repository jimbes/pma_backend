<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NotificationEmail extends Mailable
{
    public function __construct(private Notification $notification)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notification->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'notification' => $this->notification,
            ],
        );
    }
}
