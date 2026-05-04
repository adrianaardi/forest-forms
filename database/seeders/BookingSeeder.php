<?php

namespace Database\Seeders;

use App\Models\BookingAdmin;
use App\Models\BookingBilik;
use App\Models\BookingUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        BookingUser::updateOrCreate(
            ['email' => 'admin.booking@jhs.gov.my'],
            [
                'name'     => 'testing',
                'email'    => 'adriana@email.com',
                'password' => Hash::make('password123'),
            ]
        );

        $rooms = [
            ['nama_bilik' => 'Ramin',          'aras' => 'Aras 11', 'lokasi' => 'Left Wing'],
            ['nama_bilik' => 'Rengas',          'aras' => 'Aras 11', 'lokasi' => 'Right Wing'],
            ['nama_bilik' => 'Bindang',         'aras' => 'Aras 12', 'lokasi' => 'Right Wing'],
            ['nama_bilik' => 'Menggeris',       'aras' => 'Aras 13', 'lokasi' => 'Right Wing'],
            ['nama_bilik' => 'Ruang Bengkel',   'aras' => 'Aras 13', 'lokasi' => 'Right Wing'],
            ['nama_bilik' => 'Jongkong',        'aras' => 'Aras 13', 'lokasi' => 'Right Wing'],
            ['nama_bilik' => 'Engkabang',       'aras' => 'Aras 14', 'lokasi' => 'Left Wing'],
            ['nama_bilik' => 'Selangan',        'aras' => 'Aras 15', 'lokasi' => 'Right Wing'],
            ['nama_bilik' => 'Belian',          'aras' => 'Aras 15', 'lokasi' => 'Left Wing'],
        ];

        foreach ($rooms as $room) {
            BookingBilik::updateOrCreate(
                ['nama_bilik' => $room['nama_bilik']],
                $room
            );
        }
    }
}