<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorangAduanKerosakan;
use App\Models\BorangMuatNaikBahan;
use Illuminate\Http\Request;
use App\Mail\SupervisorApprovalMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\ICTStatusMail;


class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_ict'         => BorangAduanKerosakan::count(),
            'pending_upload'    => BorangMuatNaikBahan::where('status', 'Pending')->count(),
            'selesai_ict'       => BorangAduanKerosakan::where('status', 'Selesai')->count(),
            'total_upload'      => BorangMuatNaikBahan::count(),
        ];

        $recentIct    = BorangAduanKerosakan::latest()->take(3)->get();
        $recentUpload = BorangMuatNaikBahan::latest()->take(3)->get();

        return view('admin.dashboard', compact('stats', 'recentIct', 'recentUpload'));
    }

    public function ictAduan(Request $request)
    {

        $user = Auth::user();

        $query = BorangAduanKerosakan::query();

        if ($user->role === 'sub_admin') {

            $wilayah = \App\Models\Wilayah::find($user->wilayah_id);

            if ($wilayah) {
                $query->where('wilayah', $wilayah->nama_wilayah);
            }
        }

        // Admin can filter freely
        if ($user->role === 'admin') {

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('bahagian', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori_masalah', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('wilayah')) {
                $query->where('wilayah', $request->wilayah);
            }
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        return view('admin.ict-aduan', compact('complaints'));
    }

    public function portalUpload(Request $request)
    {
        if (Auth::user()->email !== 'admin.mohon@sarawak.gov.my') abort(403);

        $query = BorangMuatNaikBahan::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('tajuk_maklumat', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bahagian')) {
            $query->where('bahagian_nama', $request->bahagian);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'   => BorangMuatNaikBahan::count(),
            'pending' => BorangMuatNaikBahan::where('status', 'Pending')->count(),
            'semakan' => BorangMuatNaikBahan::where('status', 'Dalam Semakan')->count(),
        ];

        $bahagianList = \App\Models\BahagianSupervisor::orderBy('nama_bahagian')->get();

        $chartData = [
            'Pending'       => \App\Models\BorangMuatNaikBahan::where('status', 'Pending')->count(),
            'Dalam Semakan' => \App\Models\BorangMuatNaikBahan::where('status', 'Dalam Semakan')->count(),
            'Diluluskan'    => \App\Models\BorangMuatNaikBahan::where('status', 'Diluluskan')->count(),
        ];

        return view('admin.portal-upload', compact('requests', 'stats', 'bahagianList', 'chartData'));
    }

    public function dashboardMohon()
    {
        if (Auth::user()->email !== 'admin.mohon@sarawak.gov.my') abort(403);

        // permohonan by month (last 3 months)
        $months = collect(range(2, 0))->map(function($i) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            return [
                'label' => $month->translatedFormat('M Y'),
                'count' => \App\Models\BorangMuatNaikBahan::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->count(),
            ];
        });

        // jenis pengemaskinian
        $pengemaskinian = [
            'Maklumat Baru'  => \App\Models\BorangMuatNaikBahan::where('jenis_pengemaskinian', 'Maklumat Baru')->count(),
            'Pembetulan'     => \App\Models\BorangMuatNaikBahan::where('jenis_pengemaskinian', 'Pembetulan')->count(),
            'Lain-lain'      => \App\Models\BorangMuatNaikBahan::where('jenis_pengemaskinian', 'Lain-lain')->count(),
        ];

        // permohonan by bahagian
        $byBahagian = \App\Models\BorangMuatNaikBahan::selectRaw('bahagian_nama, count(*) as count')
            ->groupBy('bahagian_nama')
            ->orderByDesc('count')
            ->get();

        // status
        $status = [
            'Pending'       => \App\Models\BorangMuatNaikBahan::where('status', 'Pending')->count(),
            'Dalam Semakan' => \App\Models\BorangMuatNaikBahan::where('status', 'Dalam Semakan')->count(),
            'Diluluskan'    => \App\Models\BorangMuatNaikBahan::where('status', 'Diluluskan')->count(),
        ];

        // jenis kandungan
        $kandungan = [
            'Pengumuman'     => \App\Models\BorangMuatNaikBahan::where('jenis_kandungan', 'Pengumuman')->count(),
            'Foto'           => \App\Models\BorangMuatNaikBahan::where('jenis_kandungan', 'Foto')->count(),
            'Event'          => \App\Models\BorangMuatNaikBahan::where('jenis_kandungan', 'Event')->count(),
            'Banner/Poster'  => \App\Models\BorangMuatNaikBahan::where('jenis_kandungan', 'Banner/Poster')->count(),
            'Jawatan Kosong' => \App\Models\BorangMuatNaikBahan::where('jenis_kandungan', 'Jawatan Kosong')->count(),
            'Lain-lain'      => \App\Models\BorangMuatNaikBahan::where('jenis_kandungan', 'Lain-lain')->count(),
        ];

        $total = \App\Models\BorangMuatNaikBahan::count();

        return view('admin.dashboard-mohon', compact(
            'months', 'pengemaskinian', 'byBahagian', 'status', 'kandungan', 'total'
        ));
    }
    
    public function ictAduanDetail($id)
    {
        $item = BorangAduanKerosakan::findOrFail($id);
        return view('admin.ict-aduan-detail', compact('item'));
    }

    public function portalUploadDetail($id)
    {
        $item = BorangMuatNaikBahan::findOrFail($id);
        return view('admin.portal-upload-detail', compact('item'));
    }

    public function updateIctStatus(Request $request, $id)
    {
        $aduan = \App\Models\BorangAduanKerosakan::findOrFail($id);

        $aduan->status = $request->status;
        $aduan->remarks = $request->remarks;

        if ($request->status === 'Tindakan Pembekal SAINS/Luar') {
            $aduan->nama_syarikat = $request->nama_syarikat;
            $aduan->no_tel_syarikat = $request->no_tel_syarikat;
            $aduan->tarikh_tindakan = $request->tarikh_tindakan;
            $aduan->tarikh_selesai = $request->tarikh_selesai;
            $aduan->catatan_pembekal = $request->catatan_pembekal;
        }

        $oldStatus = $aduan->getOriginal('status');

        $aduan->save();

        // send email if status changed
        if ($oldStatus !== $aduan->status && $aduan->emel) {
            Mail::to($aduan->emel)
                ->send(new ICTStatusMail($aduan));
        }

        return back()->with('success', 'Status berjaya dikemaskini');
    }

    public function deleteIct(Request $request)
    {
        BorangAduanKerosakan::whereIn('id', $request->ids)->delete();
        return back();
    }

    public function updateUploadStatus(Request $request, $id)
    {
        $item = BorangMuatNaikBahan::findOrFail($id);
        $item->update(['status' => $request->status]);
        return back();
    }

    public function deleteUpload(Request $request)
    {
        BorangMuatNaikBahan::whereIn('id', $request->ids)->delete();
        return back();
    }

    public function resendSupervisorEmail(Request $request)
    {
        if (Auth::user()->email !== 'admin.mohon@sarawak.gov.my') abort(403);

        $ids = $request->ids ?? [];
        if (empty($ids)) return back()->with('error', 'Tiada rekod dipilih.');

        foreach ($ids as $id) {
            $item = BorangMuatNaikBahan::find($id);
            if ($item && ($item->status === 'Dalam Semakan' || $item->status === 'Pending')) {
                app(\App\Http\Controllers\BorangMuatNaikBahanController::class)->sendSupervisorEmail($item);
                $item->last_resent_at = now();
                $item->save();
            }
        }

        return back()->with('success', 'Emel telah dihantar semula kepada penyelia.');
    }
}