<?php

namespace App\Mail;

use App\Models\CoupleInvitation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PartnerInvitationEmail extends Mailable
{
    public function __construct(private CoupleInvitation $invitation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to join PMA - Couples Medical Manager',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-invitation',
            with: [
                'invitation' => $this->invitation,
                'acceptLink' => route('accept-invite', $this->invitation->token),
            ],
        );
    }
}
