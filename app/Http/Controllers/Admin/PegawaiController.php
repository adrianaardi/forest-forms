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
            'seksyen_unit' => 'required|string|max:255'
        ]);

        Pegawai::create([
            'nama' => $request->nama,
            'gred' => $request->gred,
            'seksyen_unit' => $request->seksyen_unit,
            'bahagian_id' => Auth::user()->bahagian_id,
            'is_hadir' => true
        ]);

        return redirect()->back()->with('success', 'Officer added to roster successfully.');
    }

    public function toggleAttendance($id)
    {
        // Find officer while ensuring they belong strictly to this sub-admin's division
        $pegawai = Pegawai::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail();

        $pegawai->update(['is_hadir' => !$pegawai->is_hadir]);
        
        return redirect()->back()->with('success', 'Attendance status updated.');
    }
}