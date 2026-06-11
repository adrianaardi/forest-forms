<?php

namespace App\Mail;

use App\Models\BorangMuatNaikBahan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupervisorApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $permohonan;
    public $approvalUrl;

    public function __construct(BorangMuatNaikBahan $permohonan)
    {
        $this->permohonan = $permohonan;
        $this->approvalUrl = url('/semak/' . $permohonan->token);
    }

    public function build()
    {
        return $this->subject('Permohonan Muat Naik Portal — Kelulusan Diperlukan')
                    ->view('emails.supervisor-approval');
    }
}