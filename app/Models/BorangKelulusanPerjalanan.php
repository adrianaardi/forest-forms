<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorangKelulusanPerjalanan extends Model
{
    public function getNoTiketAttribute()
    {
        return 'JHS/KP/MSD-' . $this->id .'/'. date('Y');
    }
    protected $table='borang_kelulusan_perjalanan';

    protected $fillable = [
        'nama', 'jawatan', 'bahagian', 'telefon', 'emel',
        'tarikh_perjalanan', 'destinasi_perjalanan',
        'jenis_kenderaan', 'attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

}