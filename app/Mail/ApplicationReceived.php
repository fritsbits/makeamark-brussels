<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $applicantName,
        public string $audience,
        public int $editionYear,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We received your Make a Mark application');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.application-received');
    }
}
