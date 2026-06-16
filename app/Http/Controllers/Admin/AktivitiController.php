<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktiviti;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AktivitiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_aktiviti' => 'required|string|max:255',
            'tarikh'        => 'required|date',
            'seksyen_unit'  => 'required|string|max:255',
        ]);

        Aktiviti::create([
            'nama_aktiviti' => $request->nama_aktiviti,
            'tarikh'        => $request->tarikh,
            'seksyen_unit'  => $request->seksyen_unit,
            'bahagian_id'   => Auth::user()->bahagian_id,
        ]);

        return back()->with('success', 'Aktiviti berjaya direkodkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aktiviti' => 'required|string|max:255',
            'tarikh'        => 'required|date',
            'seksyen_unit'  => 'required|string|max:255',
        ]);

        Aktiviti::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail()
            ->update([
                'nama_aktiviti' => $request->nama_aktiviti,
                'tarikh'        => $request->tarikh,
                'seksyen_unit'  => $request->seksyen_unit,
            ]);

        return back()->with('success', 'Aktiviti berjaya dikemaskini.');
    }

    public function destroy($id)
    {
        Aktiviti::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Aktiviti berjaya dipadam.');
    }
}