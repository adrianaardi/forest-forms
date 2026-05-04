<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        
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

        User::updateOrCreate(
            ['email' => 'admin.booking@sarawak.gov.my'],
            [
                'name'     => 'Admin Booking',
                'email'    => 'admin.booking@sarawak.gov.my',
                'password' => Hash::make('password123'),
            ]
        );
    }
}