<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // latest admin user
        User::updateOrCreate(
            ['email' => 'admin.kelulusanperjalanan@sarawak.gov.my'],
            [
                'name'     => 'Admin Kelulusan Perjalanan',
                'email'    => 'admin.kelulusanperjalanan@sarawak.gov.my',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'wilayah_id' => null,
            ]
        );
    }
}