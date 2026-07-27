<?php

namespace App\Mail;

use App\Models\Bilik;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $bilik;
    public $user;
    public $cancelUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, Bilik $bilik, User $user, string $cancelUrl)
    {
        $this->booking = $booking;
        $this->bilik = $bilik;
        $this->user = $user;
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [new Address($this->user->email, $this->user->name)],
            subject: 'Pengesahan Tempahan — ' . $this->bilik->nama_bilik,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'booking'   => $this->booking,
                'bilik'     => $this->bilik,
                'user'      => $this->user,
                'cancelUrl' => $this->cancelUrl,
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