<?php

namespace Database\Seeders;

use App\Models\BookingBilik;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['nama_bilik' => 'Ramin',        'aras' => 'Aras 11', 'wing' => 'Left Wing'],
            ['nama_bilik' => 'Rengas',        'aras' => 'Aras 11', 'wing' => 'Right Wing'],
            ['nama_bilik' => 'Bindang',       'aras' => 'Aras 12', 'wing' => 'Right Wing'],
            ['nama_bilik' => 'Menggeris',     'aras' => 'Aras 13', 'wing' => 'Right Wing'],
            ['nama_bilik' => 'Ruang Bengkel', 'aras' => 'Aras 13', 'wing' => 'Right Wing'],
            ['nama_bilik' => 'Jongkong',      'aras' => 'Aras 13', 'wing' => 'Right Wing'],
            ['nama_bilik' => 'Engkabang',     'aras' => 'Aras 14', 'wing' => 'Left Wing'],
            ['nama_bilik' => 'Selangan',      'aras' => 'Aras 15', 'wing' => 'Right Wing'],
            ['nama_bilik' => 'Belian',        'aras' => 'Aras 15', 'wing' => 'Left Wing'],
        ];

        foreach ($rooms as $room) {
            BookingBilik::updateOrCreate(
                ['nama_bilik' => $room['nama_bilik']],
                $room
            );
        }
    }
}