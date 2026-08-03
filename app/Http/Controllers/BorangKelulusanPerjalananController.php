<?php

namespace App\Http\Controllers;

use App\Mail\BrevoMailer;
use App\Models\BorangKelulusanPerjalanan;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorangKelulusanPerjalananController extends Controller
{
    public function create()
    {
        $bookingUser = Auth::guard('booking_user')->user();

        return view('forms.kelulusan-perjalanan', [
            'bookingUser' => $bookingUser,
        ]);
    }

    public function store(Request $request)
    {
        $bookingUser = Auth::guard('booking_user')->user();

        if (!$bookingUser) {
            return redirect()->route('kelulusan-perjalanan')
                ->withErrors(['auth' => 'Pengguna hendaklah log masuk untuk isi borang ini.']);
        }

        if (empty($bookingUser->signature)) {
            return redirect()->route('kelulusan-perjalanan')
                ->withErrors(['signature' => 'Sijil digital tidak dijumpai. Sila kemas kini tandatangan digital pada profil booking anda.'])
                ->withInput();
        }

        if (
            empty($bookingUser->bahagian)
            || empty($bookingUser->name)
            || empty($bookingUser->jawatan)
            || empty($bookingUser->phone)
            || empty($bookingUser->email)
        ) {
            return redirect()->route('kelulusan-perjalanan')
                ->withErrors(['profile' => 'Maklumat profil booking anda tidak lengkap. Sila kemas kini profil terlebih dahulu.'])
                ->withInput();
        }

        if (empty($bookingUser->supervisor_id)) {
            return redirect()->route('kelulusan-perjalanan')
                ->withErrors(['profile' => 'Supervisor belum ditetapkan. Sila pilih supervisor anda di halaman profil booking terlebih dahulu.'])
                ->withInput();
        }

        $assignedSupervisor = BookingUser::find($bookingUser->supervisor_id);
        if (!$assignedSupervisor) {
            return redirect()->route('kelulusan-perjalanan')
                ->withErrors(['profile' => 'Supervisor ditetapkan tidak ditemui. Sila tetapkan semula supervisor di halaman profil booking.'])
                ->withInput();
        }

        if (!$assignedSupervisor->is_supervisor) {
            $assignedSupervisor->is_supervisor = true;
            $assignedSupervisor->save();
        }

        $validated = $request->validate([
            'pegawai_turut_serta' => 'nullable|array',
            'pegawai_turut_serta.*' => 'nullable|string|max:255',
            'destinasi_perjalanan' => 'required|string|max:255',
            'tarikh_perjalanan' => 'required|date',
            'jenis_kenderaan' => 'required|in:kenderaan_sendiri,penerbangan_selain_air_borneo',
            'sebab_kenderaan_sendiri' => 'nullable|in:Tiada kemudahan kenderaan rasmi jabatan,Tiada perkhidmatan terus kapal terbang/lain pengangkutan,Memohon tambang gantian (jarak melebihi 240km),Lain-lain',
            'sebab_kenderaan_sendiri_lain' => 'nullable|string|max:255',
            'sebab_penerbangan_lain' => 'nullable|in:Tiada Tempat Duduk,Jadual Tidak Sesuai,Kecemasan,Destinasi Tidak Disediakan,Lain-lain',
            'sebab_penerbangan_lain_lain' => 'nullable|string|max:255',
            'dokumen_sokongan' => 'nullable|file|max:5120|mimes:pdf,png,jpg,jpeg',
        ]);

        if ($validated['jenis_kenderaan'] === 'kenderaan_sendiri' && !$request->filled('sebab_kenderaan_sendiri')) {
            return back()
                ->withErrors(['sebab_kenderaan_sendiri' => 'Sila pilih sebab untuk kenderaan sendiri.'])
                ->withInput();
        }

        if ($validated['jenis_kenderaan'] === 'penerbangan_selain_air_borneo' && !$request->filled('sebab_penerbangan_lain')) {
            return back()
                ->withErrors(['sebab_penerbangan_lain' => 'Sila pilih sebab untuk penerbangan selain Air Borneo.'])
                ->withInput();
        }

        if ($request->input('sebab_kenderaan_sendiri') === 'Lain-lain' && !$request->filled('sebab_kenderaan_sendiri_lain')) {
            return back()
                ->withErrors(['sebab_kenderaan_sendiri_lain' => 'Sila nyatakan sebab lain untuk kenderaan sendiri.'])
                ->withInput();
        }

        if ($request->input('sebab_penerbangan_lain') === 'Lain-lain' && !$request->filled('sebab_penerbangan_lain_lain')) {
            return back()
                ->withErrors(['sebab_penerbangan_lain_lain' => 'Sila nyatakan sebab lain untuk penerbangan selain Air Borneo.'])
                ->withInput();
        }

        $pegawaiTurutSerta = collect($validated['pegawai_turut_serta'] ?? [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        $attachments = null;
        if ($request->hasFile('dokumen_sokongan')) {
            $path = $request->file('dokumen_sokongan')->store('kelulusan-perjalanan', 'public');
            $attachments = [$path];
        }

        $jenisPermohonan = '';
        if ($validated['jenis_kenderaan'] === 'kenderaan_sendiri') {
            $sebab = $request->input('sebab_kenderaan_sendiri');
            if ($sebab === 'Lain-lain') {
                $sebab = 'Lain-lain: ' . $request->input('sebab_kenderaan_sendiri_lain');
            }
            $jenisPermohonan = 'Kenderaan Sendiri (' . $sebab . ')';
        }

        if ($validated['jenis_kenderaan'] === 'penerbangan_selain_air_borneo') {
            $sebab = $request->input('sebab_penerbangan_lain');
            if ($sebab === 'Lain-lain') {
                $sebab = 'Lain-lain: ' . $request->input('sebab_penerbangan_lain_lain');
            }
            $jenisPermohonan = 'Penerbangan Selain Air Borneo (' . $sebab . ')';
        }

        $borang = BorangKelulusanPerjalanan::create([
            'booking_user_id' => $bookingUser->id,
            'supervisor_user_id' => $assignedSupervisor->id,
            'bahagian' => $bookingUser->bahagian,
            'nama' => $bookingUser->name,
            'jawatan' => $bookingUser->jawatan,
            'pegawai_turut_serta' => $pegawaiTurutSerta,
            'destinasi_perjalanan' => $validated['destinasi_perjalanan'],
            'tarikh_perjalanan' => $validated['tarikh_perjalanan'],
            'jenis_kenderaan' => $jenisPermohonan,
            'telefon' => $bookingUser->phone,
            'emel' => $bookingUser->email,
            'attachments' => $attachments,
            'signature_path' => $bookingUser->signature,
            'status' => 'Pending',
        ]);

        $this->notifyAssignedSupervisor($borang, $assignedSupervisor);

        return redirect()->route('kelulusan-perjalanan')
            ->with('success', 'Borang kelulusan perjalanan telah dihantar. No Tiket: ' . $borang->no_tiket);
    }

    public function supervisorIndex()
    {
        $user = Auth::guard('booking_user')->user();

        if (!$user) {
            return redirect('/booking/login')->with('error', 'Sila log masuk sebagai supervisor.');
        }

        if (!$user->is_supervisor) {
            abort(403);
        }

        $borang = BorangKelulusanPerjalanan::with('bookingUser')
            ->where(function ($query) use ($user) {
                $query->where('supervisor_user_id', $user->id)
                    ->orWhere(function ($legacyQuery) use ($user) {
                        // Backward compatibility for old records before supervisor_user_id was set on submit.
                        $legacyQuery->whereNull('supervisor_user_id')
                            ->whereHas('bookingUser', function ($bookingUserQuery) use ($user) {
                                $bookingUserQuery->where('supervisor_id', $user->id);
                            });
                    });
            })
            ->latest()
            ->get();

        // Transform collection to prepare safe data for JS/Blade
        $borangData = $borang->map(fn ($item) => $this->formatBorangForView($item));

        return view('kelulusan-flow.supervisor-view', [
            'borang' => $borang,
            'borangData' => $borangData,
            'currentUserSignature' => $user->signature,
            'currentUserName' => $user->name,
        ]);
    }

    public function supervisorReview(Request $request, BorangKelulusanPerjalanan $borang)
    {
        $user = Auth::guard('booking_user')->user();

        if (!$user) {
            return redirect('/booking/login')->with('error', 'Sila log masuk sebagai supervisor.');
        }

        if (!$user->is_supervisor) {
            abort(403);
        }

        $isAssignedBySubmission = (int) $borang->supervisor_user_id === (int) $user->id;
        $isLegacyAssigned = (int) optional($borang->bookingUser)->supervisor_id === (int) $user->id;
        if (!$isAssignedBySubmission && !$isLegacyAssigned) {
            abort(403);
        }

        if (empty($user->signature)) {
            return back()->withErrors(['signature' => 'Sijil digital penyelia tidak dijumpai. Sila kemas kini tandatangan digital pada profil booking anda.']);
        }

        $validated = $request->validate([
            'keputusan' => 'required|in:Disokong,Tidak disokong',
        ]);

        $status = $validated['keputusan'] === 'Disokong' ? 'Menunggu HOD' : 'Tidak disokong';

        $borang->update([
            'status' => $status,
            'supervisor_status' => $validated['keputusan'],
            'supervisor_user_id' => $user->id,
            'supervisor_signature_path' => $user->signature,
            'supervisor_reviewed_at' => now(),
        ]);

        if ($validated['keputusan'] === 'Disokong') {
            $this->notifyHods($borang);
        }

        return back()->with('success', 'Keputusan berjaya dihantar.');
    }

    public function hodIndex()
    {
        $user = Auth::guard('booking_user')->user();

        if (!$user) {
            return redirect('/booking/login')->with('error', 'Sila log masuk sebagai HOD.');
        }

        if (!$user->is_hod) {
            abort(403);
        }

        $borang = BorangKelulusanPerjalanan::with('bookingUser')
            ->where('supervisor_status', 'Disokong')
            ->whereHas('bookingUser', function ($query) use ($user) {
                $query->where('wilayah_id', $user->wilayah_id)
                    ->where('bahagian', $user->bahagian);
            })
            ->latest()
            ->get();

        $borangData = $borang->map(fn ($item) => $this->formatBorangForView($item));

        return view('kelulusan-flow.HOD-view', [
            'borang' => $borang,
            'borangData' => $borangData,
            'currentUserSignature' => $user->signature,
            'currentUserName' => $user->name,
        ]);
    }

    public function hodReview(Request $request, BorangKelulusanPerjalanan $borang)
    {
        $user = Auth::guard('booking_user')->user();

        if (!$user) {
            return redirect('/booking/login')->with('error', 'Sila log masuk sebagai HOD.');
        }

        if (!$user->is_hod) {
            abort(403);
        }

        if ($borang->supervisor_status !== 'Disokong') {
            abort(403);
        }

        $applicant = $borang->bookingUser;
        if (!$this->isSameWilayahAndBahagian($user, $applicant)) {
            abort(403);
        }

        if (empty($user->signature)) {
            return back()->withErrors(['signature' => 'Sijil digital HOD tidak dijumpai. Sila kemas kini tandatangan digital pada profil booking anda.']);
        }

        $validated = $request->validate([
            'keputusan' => 'required|in:Diluluskan,Tidak diluluskan',
            'catatan' => 'required|string|max:1000',
        ]);

        $borang->update([
            'status' => $validated['keputusan'],
            'hod_status' => $validated['keputusan'],
            'hod_catatan' => $validated['catatan'],
            'hod_user_id' => $user->id,
            'hod_signature_path' => $user->signature,
            'hod_reviewed_at' => now(),
        ]);

        if ($validated['keputusan'] === 'Diluluskan') {
            $this->notifyAccountants($borang);
        }

        return back()->with('success', 'Keputusan HOD berjaya dihantar.');
    }

    public function accountantIndex()
    {
        $user = Auth::guard('booking_user')->user();

        if (!$user) {
            return redirect('/booking/login')->with('error', 'Sila log masuk sebagai akauntan.');
        }

        if (!$user->is_accountant) {
            abort(403);
        }

        $borang = BorangKelulusanPerjalanan::with('bookingUser')
            ->where('hod_status', 'Diluluskan')
            ->latest()
            ->get();

        // The printed PDF needs the supervisor's and HOD's name/jawatan for
        // their signature blocks, which formatBorangForView() doesn't carry.
        // Bulk-fetch them here rather than N+1 querying per row.
        $reviewerIds = $borang->pluck('supervisor_user_id')
            ->merge($borang->pluck('hod_user_id'))
            ->filter()
            ->unique()
            ->values();

        $reviewers = BookingUser::whereIn('id', $reviewerIds)->get()->keyBy('id');

        $borangData = $borang->map(function ($item) use ($reviewers) {
            $data = $this->formatBorangForView($item);

            $supervisor = $item->supervisor_user_id ? $reviewers->get($item->supervisor_user_id) : null;
            $hod = $item->hod_user_id ? $reviewers->get($item->hod_user_id) : null;

            $data['supervisor_name'] = $supervisor->name ?? null;
            $data['supervisor_jawatan'] = $supervisor->jawatan ?? null;
            $data['hod_name'] = $hod->name ?? null;
            $data['hod_jawatan'] = $hod->jawatan ?? null;

            return $data;
        });

        return view('kelulusan-flow.accountant-view', [
            'borang' => $borang,
            'borangData' => $borangData,
            'currentUserSignature' => $user->signature,
            'currentUserName' => $user->name,
        ]);
    }

    private function notifyHods(BorangKelulusanPerjalanan $borang): void
    {
        $applicant = $borang->bookingUser;
        if (!$applicant) {
            return;
        }

        $hods = BookingUser::where('is_hod', true)
            ->where('wilayah_id', $applicant->wilayah_id)
            ->where('bahagian', $applicant->bahagian)
            ->get();

        foreach ($hods as $hod) {
            if (!$hod->email) {
                continue;
            }

            BrevoMailer::send(
                $hod->email,
                $hod->name,
                'Borang Kelulusan Perjalanan Menunggu Semakan HOD',
                view('emails.kelulusan-perjalanan-hod', [
                    'borang' => $borang,
                    'reviewUrl' => route('kelulusan-flow.hod-view'),
                ])->render()
            );
        }
    }

    private function notifyAssignedSupervisor(BorangKelulusanPerjalanan $borang, BookingUser $supervisor): void
    {
        if (!$supervisor->email) {
            return;
        }

        BrevoMailer::send(
            $supervisor->email,
            $supervisor->name,
            'Borang Kelulusan Perjalanan Menunggu Semakan Penyelia',
            view('emails.kelulusan-perjalanan-supervisor', [
                'borang' => $borang,
                'reviewUrl' => route('kelulusan-flow.supervisor-view'),
            ])->render()
        );
    }

    private function isSameWilayahAndBahagian(BookingUser $hod, ?BookingUser $applicant): bool
    {
        if (!$applicant) {
            return false;
        }

        return (int) $hod->wilayah_id === (int) $applicant->wilayah_id
            && (string) $hod->bahagian === (string) $applicant->bahagian;
    }

    private function notifyAccountants(BorangKelulusanPerjalanan $borang): void
    {
        $accountants = BookingUser::where('is_accountant', true)->get();

        foreach ($accountants as $accountant) {
            if (!$accountant->email) {
                continue;
            }

            BrevoMailer::send(
                $accountant->email,
                $accountant->name,
                'Borang Kelulusan Perjalanan Diluluskan - Sedia Untuk Cetakan',
                view('emails.kelulusan-perjalanan-accountant', [
                    'borang' => $borang,
                    'reviewUrl' => route('kelulusan-flow.accountant-view'),
                ])->render()
            );
        }
    }

    private function formatBorangForView(BorangKelulusanPerjalanan $item): array
    {
        return [
            'id' => $item->id,
            'no_tiket' => $item->no_tiket,
            'nama' => $item->nama,
            'jawatan' => $item->jawatan,
            'bahagian' => $item->bahagian,
            'telefon' => $item->telefon,
            'emel' => $item->emel,
            'pegawai_turut_serta' => $item->pegawai_turut_serta ?? [],
            'tarikh_perjalanan' => $item->tarikh_perjalanan ? \Carbon\Carbon::parse($item->tarikh_perjalanan)->format('d/m/Y') : '-',
            'destinasi_perjalanan' => $item->destinasi_perjalanan,
            'jenis_kenderaan' => $item->jenis_kenderaan,
            'created_at' => \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i'),
            'status' => $item->status,
            'reviewed_at' => $item->reviewed_at ? \Carbon\Carbon::parse($item->reviewed_at)->format('d/m/Y H:i') : '-',
            'signature_path' => $item->signature_path,
            'supervisor_status' => $item->supervisor_status,
            'supervisor_reviewed_at' => $item->supervisor_reviewed_at ? \Carbon\Carbon::parse($item->supervisor_reviewed_at)->format('d/m/Y H:i') : '-',
            'supervisor_signature_path' => $item->supervisor_signature_path,
            'hod_status' => $item->hod_status,
            'hod_catatan' => $item->hod_catatan,
            'hod_reviewed_at' => $item->hod_reviewed_at ? \Carbon\Carbon::parse($item->hod_reviewed_at)->format('d/m/Y H:i') : '-',
            'hod_signature_path' => $item->hod_signature_path,
            'attachments' => $item->attachments ?? [],
        ];
    }
}