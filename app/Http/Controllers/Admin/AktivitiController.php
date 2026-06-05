<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktiviti;
use Illuminate\Http\Request;

class AktivitiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_aktiviti' => 'required|string|max:255',
            'tarikh' => 'required|date',
            'seksyen_unit' => 'required|string|max:100',
        ]);

        Aktiviti::create($request->all());

        return redirect()->back()->with('success', 'Program / Aktiviti Jabatan berjaya dijadualkan!');
    }
}