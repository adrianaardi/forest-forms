<?php

namespace App\Mail;

use App\Models\BorangMuatNaikBahan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public BorangMuatNaikBahan $permohonan;

    public function __construct(BorangMuatNaikBahan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengesahan Permohonan — ' . $this->permohonan->no_tiket,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-submission',
        );
    }
}