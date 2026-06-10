<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model {
    protected $fillable = [
        'nama',
        'gred',
        'bahagian_id',
        'seksyen_unit',
        'is_hadir'
    ];
    public function bahagian() { return $this->belongsTo(Bahagian::class); }
}