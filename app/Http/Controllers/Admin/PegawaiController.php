<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'gred'         => 'required|string|max:50',
            'seksyen_unit' => 'required|string|max:255',
            'remarks'      => 'nullable|string|max:255',
        ]);

        Pegawai::create([
            'nama'         => $request->nama,
            'gred'         => $request->gred,
            'seksyen_unit' => $request->seksyen_unit,
            'bahagian_id'  => Auth::user()->bahagian_id,
            'is_hadir'     => true,
            'remarks'      => $request->remarks,
        ]);

        return back()->with('success', 'Pegawai berjaya ditambah ke roster.');
    }

    public function destroy($id)
    {
        Pegawai::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Pegawai berjaya dipadam daripada roster.');
    }

    public function toggleAttendance($id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail();

        $pegawai->update(['is_hadir' => !$pegawai->is_hadir]);

        return back()->with('success', 'Status kehadiran dikemaskini.');
    }

    public function updateRemarks(Request $request, $id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail();

        $pegawai->update(['remarks' => $request->remarks]);

        return back()->with('success', 'Catatan dikemaskini.');
    }
}