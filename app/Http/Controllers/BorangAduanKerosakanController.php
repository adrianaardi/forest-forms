<?php

namespace App\Http\Controllers;

use App\Models\BorangAduanKerosakan;
use Illuminate\Http\Request;

class BorangAduanKerosakanController extends Controller
{
    public function create()
    {
        return view('forms.ict-aduan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                 => 'required|string|max:255',
            'jawatan'              => 'nullable|string|max:255',

            // EXISTING
            'bahagian'             => 'nullable|string|max:255',
            'bahagian_lain'       => 'nullable|string|max:255',

            // 🆕 WILAYAH ADDED
            'wilayah'              => 'nullable|string|max:255',
            'wilayah_lain'         => 'nullable|string|max:255',

            'telefon'              => 'nullable|string|max:50',
            'emel'                 => 'nullable|string|max:255',
            'kategori_masalah'     => 'required|string',
            'masalah_lain'         => 'nullable|string|max:255',
            'keterangan_kerosakan' => 'nullable|string',

            'attachments'          => 'nullable|array|max:5',
            'attachments.*'        => 'file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip',
        ]);

        $validated['tarikh_aduan'] = now()->toDateString();
        $validated['masa_aduan']   = now()->toTimeString();

        // ✅ CLEAN LOGIC FOR BAHAGIAN
        $validated['bahagian'] = $request->bahagian == 'lain'
            ? $request->bahagian_lain
            : $request->bahagian;

        // 🆕 CLEAN LOGIC FOR WILAYAH
        $validated['wilayah'] = $request->wilayah == 'lain'
            ? $request->wilayah_lain
            : $request->wilayah;

        $files = [];

        if ($request->hasFile('attachments')) {

            if (count($request->file('attachments')) > 5) {
                return back()
                    ->withErrors(['attachments' => 'Maksimum 5 fail sahaja dibenarkan.'])
                    ->withInput();
            }

            foreach ($request->file('attachments') as $file) {
                $files[] = $file->store('complaints', 'public');
            }
        }

        $validated['attachments'] = json_encode($files);

        $complaint = BorangAduanKerosakan::create($validated);

        return redirect('/')->with(
            'success',
            'Aduan ICT anda telah berjaya dihantar! Anda akan menerima e-mel dengan No. tiket: ' . $complaint->no_tiket
        );
    }
}