<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktiviti;
use App\Models\Bahagian;
use App\Models\News;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function pergerakan(Request $request)
    {
        $bahagianList        = Bahagian::orderBy('nama')->get();
        $selectedBahagianId  = $request->input('bahagian_id') ?: $bahagianList->first()?->id;
        $search              = trim((string) $request->input('search', ''));

        $pegawaiQuery = Pegawai::query()->with('bahagian')->orderBy('gred', 'desc')->orderBy('nama');

        if ($selectedBahagianId) {
            $pegawaiQuery->where('bahagian_id', $selectedBahagianId);
        }
        if ($search !== '') {
            $pegawaiQuery->where('nama', 'like', '%' . $search . '%');
        }

        $pegawaiList  = $pegawaiQuery->paginate(10);
        $aktivitiList = $this->aktivitiMingguIni($selectedBahagianId);
        $newsTicker   = News::where('bahagian_id', $selectedBahagianId)
                            ->latest()
                            ->pluck('headline')
                            ->implode(' • ');

        return view('display.pergerakan', compact(
            'bahagianList', 'selectedBahagianId', 'search', 'pegawaiList', 'aktivitiList', 'newsTicker'
        ));
    }

    public function fullDisplay(Request $request)
    {
        $selectedBahagianId = $request->input('bahagian_id') ?: Bahagian::orderBy('nama')->first()?->id;
        $search             = trim((string) $request->input('search', ''));

        $pegawaiQuery = Pegawai::query()->with('bahagian')->orderBy('gred', 'desc')->orderBy('nama');

        if ($selectedBahagianId) {
            $pegawaiQuery->where('bahagian_id', $selectedBahagianId);
        }
        if ($search !== '') {
            $pegawaiQuery->where('nama', 'like', '%' . $search . '%');
        }

        $pegawaiList  = $pegawaiQuery->get();
        $aktivitiList = $this->aktivitiMingguIni($selectedBahagianId);
        $newsTicker   = News::where('bahagian_id', $selectedBahagianId)
                            ->latest()
                            ->pluck('headline')
                            ->implode(' • ');

        return view('display.full-display', compact('pegawaiList', 'aktivitiList', 'newsTicker'));
    }

    private function aktivitiMingguIni($bahagianId)
    {
        return Aktiviti::where('bahagian_id', $bahagianId)
            ->where('tarikh', '>=', now()->subDays(7)->toDateString())
            ->orderBy('tarikh')
            ->take(8)
            ->get();
    }
}