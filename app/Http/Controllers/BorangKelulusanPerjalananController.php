<?php
namespace App\Http\Controllers;
use App\Models\BorangKelulusanPerjalanan;

class BorangKelulusanPerjalananController extends Controller
{
    public function create()
    {
        $borang = BorangKelulusanPerjalanan::all();
        return view('forms.kelulusan-perjalanan', compact('borang'));
    }
    public function store()
    {
        $data = request()->validate([
            'nama' => 'required|string',
            'jawatan' => 'required|string',
            'bahagian' => 'required|string',
            'telefon' => 'required|string',
            'emel' => 'required|email',
            'tarikh_perjalanan' => 'required|date',
            'destinasi_perjalanan' => 'required|string',
            'jenis_kenderaan' => 'required|string',
            'attachments' => 'nullable|array',
        ]);

        $borang = BorangKelulusanPerjalanan::create($data);

        return redirect()->route('forms.kelulusan-perjalanan')->with('success', 'Borang kelulusan perjalanan telah dihantar. No Tiket: '.$borang->no_tiket);
    }
}