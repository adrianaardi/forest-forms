<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ICTSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $aduan;

    public function __construct($aduan)
    {
        $this->aduan = $aduan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengesahan Aduan ICT — ' . $this->aduan->no_tiket,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ict-submission',
            with: [
                'aduan' => $this->aduan,
            ],
        );
    }
}