<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function storePegawai(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gred' => 'required|string|max:50',
            'seksyen_unit' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        Pegawai::create([
            'nama' => $request->nama,
            'gred' => $request->gred,
            'seksyen_unit' => $request->seksyen_unit,
            'bahagian_id' => Auth::user()->bahagian_id,
            'is_hadir' => true,
            'remarks'=> $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Officer added to roster successfully.');
    }

    public function toggleAttendance($id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail();

        $pegawai->update([
            'is_hadir' => !$pegawai->is_hadir,
        ]);

        return back()->with('success', 'Attendance status updated.');
    }

    public function updateRemarks(Request $request, $id)
    {
        $pegawai = \App\Models\Pegawai::findOrFail($id);

        $pegawai->update([
            'remarks' => $request->remarks
        ]);

        return back()->with('success', 'Remarks updated successfully');
    }
}