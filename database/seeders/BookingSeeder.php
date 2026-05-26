<?php

namespace Database\Seeders;

use App\Models\BookingBilik;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $ibuPejabat = \App\Models\Wilayah::where('nama_wilayah', 'Ibu Pejabat')->first();
        $miri       = \App\Models\Wilayah::where('nama_wilayah', 'Miri')->first();
        $bintulu    = \App\Models\Wilayah::where('nama_wilayah', 'Bintulu')->first();
        $sibu       = \App\Models\Wilayah::where('nama_wilayah', 'Sibu')->first();
        $limbang    = \App\Models\Wilayah::where('nama_wilayah', 'Limbang')->first();
        $lawas      = \App\Models\Wilayah::where('nama_wilayah', 'Lawas')->first();
        $kapit      = \App\Models\Wilayah::where('nama_wilayah', 'Kapit')->first();
        $sarikei    = \App\Models\Wilayah::where('nama_wilayah', 'Sarikei')->first();
        $kuching    = \App\Models\Wilayah::where('nama_wilayah', 'Kuching')->first();

        $rooms = [
            // Ibu Pejabat
            ['nama_bilik' => 'Ramin',        'aras' => 'Aras 11', 'wing' => 'Left Wing',  'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Rengas',       'aras' => 'Aras 11', 'wing' => 'Right Wing', 'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Bindang',      'aras' => 'Aras 12', 'wing' => 'Right Wing', 'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Menggeris',    'aras' => 'Aras 13', 'wing' => 'Right Wing', 'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Ruang Bengkel','aras' => 'Aras 13', 'wing' => 'Right Wing', 'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Jongkong',     'aras' => 'Aras 13', 'wing' => 'Right Wing', 'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Engkabang',    'aras' => 'Aras 14', 'wing' => 'Left Wing',  'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Selangan',     'aras' => 'Aras 15', 'wing' => 'Right Wing', 'wilayah_id' => $ibuPejabat?->id],
            ['nama_bilik' => 'Belian',       'aras' => 'Aras 15', 'wing' => 'Left Wing',  'wilayah_id' => $ibuPejabat?->id],

            // Other wilayahs
            ['nama_bilik' => 'Bilik Mesyuarat', 'aras' => 'Tingkat 6', 'wing' => '-', 'wilayah_id' => $miri?->id],
            ['nama_bilik' => 'Sri Belian',                'aras' => 'Tingkat 3',         'wing' => '-', 'wilayah_id' => $bintulu?->id],
            ['nama_bilik' => 'Bilik Mesyuarat','aras' => 'Tingkat 12','wing' => '-', 'wilayah_id' => $sibu?->id],
            ['nama_bilik' => 'Bilik Mesyuarat','aras' => 'Tingkat 13','wing' => '-', 'wilayah_id' => $sibu?->id],

            ['nama_bilik' => 'Nepenthes',                 'aras' => 'Tingkat 3',         'wing' => '-', 'wilayah_id' => $limbang?->id],
            ['nama_bilik' => 'Bilik Mesyuarat', 'aras' => 'Tingkat 2', 'wing' => '-', 'wilayah_id' => $lawas?->id],
            ['nama_bilik' => 'Bilik Mesyuarat', 'aras' => 'Tingkat 5', 'wing' => '-', 'wilayah_id' => $kapit?->id],
            ['nama_bilik' => 'Bilik Mesyuarat', 'aras' => 'Tingkat 5', 'wing' => '-', 'wilayah_id' => $sarikei?->id],
            ['nama_bilik' => 'Bilik Mesyuarat', 'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $kuching?->id],
        ];

        foreach ($rooms as $room) {
            BookingBilik::updateOrCreate(
                [
                    'nama_bilik' => $room['nama_bilik'],
                    'wilayah_id' => $room['wilayah_id'],
                    'aras'       => $room['aras'],
                ],
                $room
            );
}
    }
}