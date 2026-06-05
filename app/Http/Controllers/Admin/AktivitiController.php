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
            'biodata' => 'required|string|max:255'
        ], [], [
            'biodata' => 'Seksyen/Unit' // Custom attribute display name
        ]);

        Aktiviti::create([
            'nama_aktiviti' => $request->nama_aktiviti,
            'tarikh' => $request->tarikh,
            'biodata' => $request->biodata,
            'bahagian_id' => Auth::user()->bahagian_id
        ]);

        return redirect()->back()->with('success', 'External activity logged successfully.');
    }
}