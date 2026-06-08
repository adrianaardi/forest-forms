<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model {
    protected $fillable = ['nama', 'gred', 'biodata', 'is_hadir', 'bahagian_id'];

    public function bahagian() { return $this->belongsTo(Bahagian::class); }
}