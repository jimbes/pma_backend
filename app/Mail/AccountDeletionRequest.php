<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccountDeletionRequest extends Mailable
{
    public function __construct(
        public string $requesterEmail,
        public ?string $message,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Demande de suppression de compte LTMO - ' . $this->requesterEmail,
            replyTo: $this->requesterEmail,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-deletion-request',
            with: [
                'requesterEmail' => $this->requesterEmail,
                'message' => $this->message,
            ],
        );
    }
}
