<?php

namespace App\Mail;

use App\Models\BorangMuatNaikBahan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $permohonan;

    public function __construct(BorangMuatNaikBahan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function build()
    {
        return $this->subject('Pengesahan Permohonan — ' . $this->permohonan->no_tiket)
                    ->view('emails.user-submission');
    }
}