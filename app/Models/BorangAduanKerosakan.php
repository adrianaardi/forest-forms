<?php

namespace App\Models;

use App\Models\Traits\HasNamaTitleCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'nama', 'jawatan', 'bahagian', 'wilayah', 'wilayah_id', 'telefon', 'emel',
        'tarikh_aduan', 'masa_aduan',
        'kategori_masalah', 'masalah_lain',
        'keterangan_kerosakan', 'status', 'attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function wilayahRef(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function getNamaWilayahAttribute(): ?string
    {
        return $this->wilayah ?: optional($this->wilayahRef)->nama_wilayah;
    }
}