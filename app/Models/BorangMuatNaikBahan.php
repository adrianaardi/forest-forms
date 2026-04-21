<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorangMuatNaikBahan extends Model
{
    public function getNoTiketAttribute()
    {
        return 'JHS/MNB/A/' . date('Y') . '(' . $this->id . ')';
    }
    protected $table = 'borang_muat_naik_bahan';

    protected $fillable = [
        'nama', 'jawatan', 'bahagian', 'telefon_email',
        'tajuk_maklumat', 'isi_kandungan',
        'jenis_kandungan', 'kandungan_lain',
        'jenis_pengemaskinian', 'pengemaskinian_lain',
        'fail_path', 'tarikh_mula', 'tarikh_akhir', 'status',
    ];
}