<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\BookingUser;

class BorangKelulusanPerjalanan extends Model
{
    public function getNoTiketAttribute()
    {
        return 'JHS/KP/MSD-' . $this->id .'/'. date('Y');
    }
    protected $table='borang_kelulusan_perjalanan';

    protected $fillable = [
        'booking_user_id', 'nama', 'jawatan', 'bahagian', 'telefon', 'emel',
        'pegawai_turut_serta',
        'tarikh_perjalanan', 'destinasi_perjalanan',
        'jenis_kenderaan', 'attachments',
        'signature_path', 'status', 'reviewed_at',
        'supervisor_user_id', 'supervisor_signature_path', 'supervisor_status', 'supervisor_reviewed_at',
        'hod_user_id', 'hod_signature_path', 'hod_status', 'hod_catatan', 'hod_reviewed_at'
    ];

    protected $casts = [
        'pegawai_turut_serta' => 'array',
        'attachments' => 'array',
        'reviewed_at' => 'datetime',
        'supervisor_reviewed_at' => 'datetime',
        'hod_reviewed_at' => 'datetime',
    ];

    public function bookingUser(): BelongsTo
    {
        return $this->belongsTo(BookingUser::class, 'booking_user_id');
    }

}