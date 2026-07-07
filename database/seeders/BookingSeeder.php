<?php

namespace Database\Seeders;

use App\Models\BookingBilik;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
	    $IFRCSemenggok = \App\Models\Wilayah::where('nama_wilayah', 'IFRC Semenggok')->first();

        $rooms = [
            ['nama_bilik' => 'Conference Room',        'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $IFRCSemenggok?->id],
            ['nama_bilik' => 'Bilik Mesyuarat', 'aras' => 'Tingkat G', 'wing' => '-', 'wilayah_id' => $IFRCSemenggok?->id],
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