<?php

namespace Database\Seeders;

use App\Models\BookingBilik;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
	    $rdd = \App\Models\Wilayah::where('nama_wilayah', 'RDD')->first();

        $rooms = [
            ['nama_bilik' => 'Bilik Mesyuarat A',        'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
            ['nama_bilik' => 'Bilik Mesyuarat Mycology', 'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
            ['nama_bilik' => 'Bilik Mesyuarat Entomology','aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
            ['nama_bilik' => 'Bilik Mesyuarat Botany',    'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
            ['nama_bilik' => 'Bilik Mesyuarat Paedology', 'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
            ['nama_bilik' => 'Bilik Latihan',             'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
            ['nama_bilik' => 'Dewan Serbaguna RDD',       'aras' => 'Tingkat 1', 'wing' => '-', 'wilayah_id' => $rdd?->id],
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