<?php

namespace App\Http\Controllers;

use App\Models\BorangAduanKerosakan;
use Illuminate\Http\Request;

//good for straightforward forms
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
            'bahagian'             => 'nullable|string|max:255',
            'telefon'              => 'nullable|string|max:50',
            'kategori_masalah'     => 'required|string',
            'masalah_lain'         => 'nullable|string|max:255',
            'keterangan_kerosakan' => 'nullable|string',
            'attachments'          => 'nullable|array|max:5',
            'attachments.*'        => 'file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip',
        ]);

        $validated['tarikh_aduan'] = now()->toDateString();
        $validated['masa_aduan']   = now()->toTimeString();

        $files = [];

        if ($request->hasFile('attachments')) {

            // hard safety check (backend protection)
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