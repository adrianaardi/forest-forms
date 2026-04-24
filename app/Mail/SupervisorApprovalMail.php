<?php

namespace App\Mail;

use App\Models\BorangMuatNaikBahan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupervisorApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public BorangMuatNaikBahan $permohonan;
    public string $approvalUrl;

    public function __construct(BorangMuatNaikBahan $permohonan)
    {
        $this->permohonan  = $permohonan;
        $this->approvalUrl = url('/semak/' . $permohonan->token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permohonan Muat Naik Portal — Kelulusan Diperlukan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.supervisor-approval',
        );
    }
}