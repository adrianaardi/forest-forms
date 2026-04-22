<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorangAduanKerosakan extends Model
{
    public function getNoTiketAttribute()
    {
        return 'JHS/ICT/A/' . date('Y') . '(' . $this->id .')';
    }

    protected $table='borang_aduan_kerosakan';

    protected $fillable = [
        'nama', 'jawatan', 'bahagian', 'telefon',
        'tarikh_aduan', 'masa_aduan',
        'kategori_masalah', 'masalah_lain',
        'keterangan_kerosakan', 'status', 'attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];
}