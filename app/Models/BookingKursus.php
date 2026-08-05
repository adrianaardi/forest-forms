<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingKursus extends Model
{
    protected $table = 'booking_kursuses';

    protected $fillable = [
        'tajuk',
        'penganjur',
        'ringkasan',
        'lokasi',
        'tarikh_mula',
        'tarikh_tamat',
        'jumlah_tempat',
        'yuran',
        'is_dalam_sarawak',
        'created_by',
    ];

    protected $casts = [
        'tarikh_mula' => 'date',
        'tarikh_tamat' => 'date',
        'jumlah_tempat' => 'integer',
        'yuran' => 'decimal:2',
        'is_dalam_sarawak' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(BookingUser::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(BookingKursusApplication::class, 'kursus_id');
    }
}