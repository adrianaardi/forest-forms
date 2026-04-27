<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ICTStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $aduan;

    /**
     * Create a new message instance.
     */
    public function __construct($aduan)
    {
        $this->aduan = $aduan;
    }

    /**
     * Email subject (header title)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kemaskini Status Aduan ICT — ' . $this->aduan->no_tiket,
        );
    }

    /**
     * Email content (blade view + data)
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ict-status',
            with: [
                'aduan' => $this->aduan,
            ],
        );
    }
}