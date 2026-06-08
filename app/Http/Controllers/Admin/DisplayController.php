<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktiviti;
use App\Models\Bahagian;
use App\Models\News;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function pergerakan(Request $request)
    {
        $bahagianList = Bahagian::orderBy('nama')->get();
        $selectedBahagianId = $request->input('bahagian_id');
        $search = trim((string) $request->input('search', ''));

        $pegawaiQuery = Pegawai::query()->with('bahagian')->orderBy('nama');

        if ($selectedBahagianId) {
            $pegawaiQuery->where('bahagian_id', $selectedBahagianId);
        }

        if ($search !== '') {
            $pegawaiQuery->where('nama', 'like', '%' . $search . '%');
        }

        $pegawaiList = $pegawaiQuery->get();

        $aktivitiList = Aktiviti::query()
            ->when($selectedBahagianId, fn ($query) => $query->where('bahagian_id', $selectedBahagianId))
            ->latest('tarikh')
            ->take(8)
            ->get();

        $newsTicker = News::where('is_active', true)->latest('created_at')->pluck('headline')->implode(' • ');

        return view('display.pergerakan', compact('bahagianList', 'selectedBahagianId', 'search', 'pegawaiList', 'aktivitiList', 'newsTicker'));
    }

    public function fullDisplay(Request $request)
    {
        $selectedBahagianId = $request->input('bahagian_id');
        $search = trim((string) $request->input('search', ''));

        $pegawaiQuery = Pegawai::query()->with('bahagian')->orderBy('nama');

        if ($selectedBahagianId) {
            $pegawaiQuery->where('bahagian_id', $selectedBahagianId);
        }

        if ($search !== '') {
            $pegawaiQuery->where('nama', 'like', '%' . $search . '%');
        }

        $pegawaiList = $pegawaiQuery->get();
        $aktivitiList = Aktiviti::query()
            ->when($selectedBahagianId, fn ($query) => $query->where('bahagian_id', $selectedBahagianId))
            ->latest('tarikh')
            ->take(8)
            ->get();

        $newsTicker = News::where('is_active', true)->latest('created_at')->pluck('headline')->implode(' • ');

        return view('display.full-display', compact('pegawaiList', 'aktivitiList', 'newsTicker'));
    }
}
