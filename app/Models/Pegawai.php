<?php

namespace App\Models;

use App\Models\Traits\HasNamaTitleCase;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model {
    use HasNamaTitleCase;
    protected $fillable = [
        'nama',
        'gred',
        'bahagian_id',
        'seksyen_unit',
        'is_hadir',
        'remarks',
    ];
    public function bahagian() { return $this->belongsTo(Bahagian::class); }
}