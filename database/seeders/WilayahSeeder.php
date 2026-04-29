<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wilayahs')->insert([
            ['nama_wilayah' => 'Kuching'],
            ['nama_wilayah' => 'Sibu'],
            ['nama_wilayah' => 'Miri'],
            ['nama_wilayah' => 'Bintulu'],
            ['nama_wilayah' => 'Sri Aman'],
            ['nama_wilayah' => 'Sarikei'],
            ['nama_wilayah' => 'Kapit'],
            ['nama_wilayah' => 'Limbang'],
        ]);
    }
}
