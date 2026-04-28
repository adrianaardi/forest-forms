<?php

namespace App\Http\Controllers;

use App\Mail\BrevoMailer;
use App\Models\BahagianSupervisor;
use App\Models\BorangMuatNaikBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'fail'                 => 'nullable|array|max:5',
            'fail.*'               => 'nullable|file|max:512000|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
            'tarikh_mula'          => 'nullable|date',
            'tarikh_akhir'         => 'nullable|date|after_or_equal:tarikh_mula',
        ]);

        $bahagian = BahagianSupervisor::findOrFail($request->bahagian_id);

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

        $this->sendSupervisorEmail($upload);

        if ($this->hasEmail($upload->telefon_email)) {
            BrevoMailer::send(
                $upload->telefon_email,
                $upload->nama,
                'Pengesahan Permohonan — ' . $upload->no_tiket,
                view('emails.user-submission', ['permohonan' => $upload])->render()
            );
        }

        return redirect('/')->with('success', 'Permohonan berjaya dihantar! Sila semak emel anda. No. Tiket: ' . $upload->no_tiket);
    }

    public function supervisorView($token)
    {
        $permohonan = BorangMuatNaikBahan::where('token', $token)->firstOrFail();
        return view('supervisor.review', compact('permohonan'));
    }

    public function supervisorApprove(Request $request, $token)
    {
        $permohonan = BorangMuatNaikBahan::where('token', $token)->firstOrFail();

        $request->validate([
            'catatan_semakan' => 'required|string|max:500',
        ]);

        $newStatus = $request->status_override === 'Dalam Semakan' ? 'Dalam Semakan' : 'Diluluskan';

        $permohonan->update([
            'status'          => $newStatus,
            'catatan_semakan' => $request->catatan_semakan,
        ]);

        if ($this->hasEmail($permohonan->telefon_email)) {
            BrevoMailer::send(
                $permohonan->telefon_email,
                $permohonan->nama,
                'Status Permohonan — ' . $permohonan->no_tiket,
                view('emails.user-status', ['permohonan' => $permohonan])->render()
            );
        }

        return $newStatus === 'Dalam Semakan'
            ? view('supervisor.semakan', compact('permohonan'))
            : view('supervisor.done', compact('permohonan'));
    }

    // ── helpers ──────────────────────────────────────────

    public function sendSupervisorEmail(BorangMuatNaikBahan $upload): void
    {
        BrevoMailer::send(
            $upload->supervisor_email,
            $upload->bahagian_nama,
            'Permohonan Muat Naik Portal — Kelulusan Diperlukan',
            view('emails.supervisor-approval', [
                'permohonan'  => $upload,
                'approvalUrl' => url('/semak/' . $upload->token),
            ])->render()
        );
    }

    private function hasEmail(?string $value): bool
    {
        return $value && str_contains($value, '@');
    }
}