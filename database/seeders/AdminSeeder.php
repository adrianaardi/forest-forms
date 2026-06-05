<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Aduan ICT
        User::updateOrCreate(
            ['email' => 'admin.aduan@sarawak.gov.my'],
            [
                'name'     => 'Admin Aduan',
                'email'    => 'admin.aduan@sarawak.gov.my',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'wilayah_id' => null,
            ]
        );

        // 2. Admin Portal Upload (Bahan)
        User::updateOrCreate(
            ['email' => 'admin.mohon@sarawak.gov.my'],
            [
                'name'     => 'Admin Mohon',
                'email'    => 'admin.mohon@sarawak.gov.my',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'wilayah_id' => null,
            ]
        );

        // 3. Admin Bilik Booking
        User::updateOrCreate(
            ['email' => 'admin.booking@sarawak.gov.my'],
            [
                'name'     => 'Admin Booking',
                'email'    => 'admin.booking@sarawak.gov.my',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // 4. Admin Sistem Pergerakan Pegawai
        User::updateOrCreate(
            ['email' => 'admin.pergerakan@sarawak.gov.my'],
            [
                'name'     => 'Admin Pergerakan Pegawai',
                'email'    => 'admin.pergerakan@sarawak.gov.my',
                'password' => Hash::make('password123'), // Matches your testing password standard
                'role'     => 'admin',
                'wilayah_id' => null, // Kept for schema consistency with your other admin rows
            ]
        );
    }
}