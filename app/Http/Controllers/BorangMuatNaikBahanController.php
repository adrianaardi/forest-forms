<?php

namespace App\Http\Controllers;

use App\Mail\SupervisorApprovalMail;
use App\Mail\UserStatusMail;
use App\Models\BahagianSupervisor;
use App\Models\BorangMuatNaikBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserSubmissionMail;

class BorangMuatNaikBahanController extends Controller
{
    public function create()
    {
        $bahagian = BahagianSupervisor::orderBy('nama_bahagian')->get();
        return view('forms.portal-upload', compact('bahagian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                 => 'required|string|max:255',
            'jawatan'              => 'nullable|string|max:255',
            'bahagian_id'          => 'required|exists:bahagian_supervisors,id',
            'telefon_email'        => 'nullable|string|max:255',
            'tajuk_maklumat'       => 'required|string|max:255',
            'isi_kandungan'        => 'nullable|string',
            'jenis_kandungan'      => 'required|string',
            'kandungan_lain'       => 'nullable|string|max:255',
            'jenis_pengemaskinian' => 'required|string',
            'pengemaskinian_lain'  => 'nullable|string|max:255',
            'fail.*'               => 'nullable|file|max:512000|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
            'fail'                 => 'nullable|array|max:5',
            'tarikh_mula'          => 'nullable|date',
            'tarikh_akhir'         => 'nullable|date|after_or_equal:tarikh_mula',
        ]);

        $bahagian = BahagianSupervisor::findOrFail($request->bahagian_id);

        // handle multiple files
        $failPaths = [];
        if ($request->hasFile('fail')) {
            foreach ($request->file('fail') as $file) {
                $failPaths[] = $file->store('uploads', 'public');
            }
        }

        $upload = BorangMuatNaikBahan::create([
            'nama'                 => $request->nama,
            'jawatan'              => $request->jawatan,
            'bahagian_id'          => $bahagian->id,
            'bahagian_nama'        => $bahagian->nama_bahagian,
            'telefon_email'        => $request->telefon_email,
            'tajuk_maklumat'       => $request->tajuk_maklumat,
            'isi_kandungan'        => $request->isi_kandungan,
            'jenis_kandungan'      => $request->jenis_kandungan,
            'kandungan_lain'       => $request->kandungan_lain,
            'jenis_pengemaskinian' => $request->jenis_pengemaskinian,
            'pengemaskinian_lain'  => $request->pengemaskinian_lain,
            'fail_paths'           => $failPaths,
            'tarikh_mula'          => $request->tarikh_mula,
            'tarikh_akhir'         => $request->tarikh_akhir,
            'supervisor_email'     => $bahagian->email_supervisor,
            'token'                => Str::random(40),
        ]);

        // send email to supervisor
        Mail::to($bahagian->email_supervisor)->send(new SupervisorApprovalMail($upload));

        // send confirmation email to user if they provided an email
        if ($upload->telefon_email && str_contains($upload->telefon_email, '@')) {
            Mail::to($upload->telefon_email)->send(new UserSubmissionMail($upload));
        }

        return redirect('/')->with('success', 'Permohonan muat naik telah berjaya dihantar! No. Tiket anda: ' . $upload->no_tiket);    }

    public function supervisorView($token)
    {
        $permohonan = BorangMuatNaikBahan::where('token', $token)->firstOrFail();
        return view('supervisor.review', compact('permohonan'));
    }

    public function supervisorApprove(Request $request, $token)
    {
        $permohonan = BorangMuatNaikBahan::where('token', $token)->firstOrFail();

        $request->validate([
            'catatan_semakan' => 'nullable|string|max:500',
        ]);

        $newStatus = $request->status_override === 'Dalam Semakan' ? 'Dalam Semakan' : 'Diluluskan';

        $permohonan->update([
            'status'          => $newStatus,
            'catatan_semakan' => $request->catatan_semakan,
        ]);

        if ($newStatus === 'Diluluskan' && $permohonan->telefon_email && str_contains($permohonan->telefon_email, '@')) {
            Mail::to($permohonan->telefon_email)->send(new UserStatusMail($permohonan));
        } elseif ($newStatus === 'Dalam Semakan' && $permohonan->telefon_email && str_contains($permohonan->telefon_email, '@')) {
            Mail::to($permohonan->telefon_email)->send(new UserStatusMail($permohonan));
        }

        return view('supervisor.done', compact('permohonan'));
    }
}