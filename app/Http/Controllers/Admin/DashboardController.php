<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorangAduanKerosakan;
use App\Models\BorangMuatNaikBahan;
use Illuminate\Http\Request;

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
        $query = BorangAduanKerosakan::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('bahagian', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori_masalah', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        return view('admin.ict-aduan', compact('complaints'));
    }

    public function portalUpload(Request $request)
    {
        $query = BorangMuatNaikBahan::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('tajuk_maklumat', 'like', '%' . $request->search . '%')
                  ->orWhere('jenis_kandungan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('admin.portal-upload', compact('requests'));
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
        $item = BorangAduanKerosakan::findOrFail($id);
        $item->update(['status' => $request->status]);
        return back();
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
}