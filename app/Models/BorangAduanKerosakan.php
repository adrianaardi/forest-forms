<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorangAduanKerosakan extends Model
{
    protected $table='borang_aduan_kerosakan';

    protected $fillable = [
        'nama', 'jawatan', 'bahagian', 'telefon',
        'tarikh_aduan', 'masa_aduan',
        'kategori_masalah', 'masalah_lain',
        'keterangan_kerosakan', 'status',
    ];
}