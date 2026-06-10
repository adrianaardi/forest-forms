<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktiviti;
use Illuminate\Support\Facades\Auth;

class AktivitiController extends Controller
{
    public function storeAktiviti(Request $request)
    {
        $request->validate([
            'nama_aktiviti' => 'required|string|max:255',
            'tarikh' => 'required|date',
            'seksyen_unit' => 'required|string|max:255'
        ]);

        Aktiviti::create([
            'nama_aktiviti' => $request->nama_aktiviti,
            'tarikh' => $request->tarikh,
            'seksyen_unit' => $request->seksyen_unit,
            'bahagian_id' => Auth::user()->bahagian_id
        ]);

        return redirect()->back()->with('success', 'Aktiviti berjaya direkodkan.');
    }
}