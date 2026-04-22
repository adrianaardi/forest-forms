<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorangMuatNaikBahan extends Model
{
    protected $table = 'borang_muat_naik_bahan';

    protected $fillable = [
        'nama', 'jawatan', 'bahagian_id', 'bahagian_nama', 'telefon_email',
        'tajuk_maklumat', 'isi_kandungan',
        'jenis_kandungan', 'kandungan_lain',
        'jenis_pengemaskinian', 'pengemaskinian_lain',
        'fail_paths', 'tarikh_mula', 'tarikh_akhir', 'status',
        'supervisor_email', 'token', 'catatan_semakan',
    ];

    protected $casts = [
        'fail_paths' => 'array',
    ];

    public function bahagian()
    {
        return $this->belongsTo(BahagianSupervisor::class, 'bahagian_id');
    }

    public function getNoTiketAttribute()
    {
        return 'JHS/MNB/A/' . date('Y') . '(' . $this->id . ')';
    }
}