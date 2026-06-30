<?php

namespace App\Models;

use App\Models\Traits\HasNamaTitleCase;
use Illuminate\Database\Eloquent\Model;

class BorangAduanKerosakan extends Model
{
    use HasNamaTitleCase;
    public function getNoTiketAttribute()
    {
        return 'JHS/ICT/A/' . date('Y') . '(' . $this->id .')';
    }

    protected $table='borang_aduan_kerosakan';

    protected $fillable = [
        'nama', 'jawatan', 'bahagian','wilayah', 'telefon', 'emel',
        'tarikh_aduan', 'masa_aduan',
        'kategori_masalah', 'masalah_lain',
        'keterangan_kerosakan', 'status', 'attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];
}