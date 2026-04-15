<?php

namespace App\Http\Controllers;

use App\Models\BorangMuatNaikBahan;
use Illuminate\Http\Request;

//suitable for complicated forms that handles file uploads etc.

class BorangMuatNaikBahanController extends Controller
{
    public function create()
    {
        return view('forms.portal-upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                 => 'required|string|max:255',
            'jawatan'              => 'nullable|string|max:255',
            'bahagian'             => 'nullable|string|max:255',
            'telefon_email'        => 'nullable|string|max:255',
            'tajuk_maklumat'       => 'required|string|max:255',
            'isi_kandungan'        => 'nullable|string',
            'jenis_kandungan'      => 'required|string',
            'kandungan_lain'       => 'nullable|string|max:255',
            'jenis_pengemaskinian' => 'required|string',
            'pengemaskinian_lain'  => 'nullable|string|max:255',
            'fail'                 => 'nullable|file|max:10240',
            'tarikh_mula'          => 'nullable|date',
            'tarikh_akhir'         => 'nullable|date|after_or_equal:tarikh_mula',
        ]);

        $failPath = null;
        if ($request->hasFile('fail')) {
            $failPath = $request->file('fail')->store('uploads', 'public');
        }

        BorangMuatNaikBahan::create([
            'nama'                 => $request->nama,
            'jawatan'              => $request->jawatan,
            'bahagian'             => $request->bahagian,
            'telefon_email'        => $request->telefon_email,
            'tajuk_maklumat'       => $request->tajuk_maklumat,
            'isi_kandungan'        => $request->isi_kandungan,
            'jenis_kandungan'      => $request->jenis_kandungan,
            'kandungan_lain'       => $request->kandungan_lain,
            'jenis_pengemaskinian' => $request->jenis_pengemaskinian,
            'pengemaskinian_lain'  => $request->pengemaskinian_lain,
            'fail_path'            => $failPath,
            'tarikh_mula'          => $request->tarikh_mula,
            'tarikh_akhir'         => $request->tarikh_akhir,
        ]);

        return redirect('/')->with('success', 'Permohonan muat naik telah berjaya dihantar!');
    }
}