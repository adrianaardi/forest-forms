<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingKursusApplication extends Model
{
    protected $table = 'booking_kursus_applications';

    protected $fillable = [
        'kursus_id',
        'booking_user_id',
        'status',
    ];

    public function kursus(): BelongsTo
    {
        return $this->belongsTo(BookingKursus::class, 'kursus_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(BookingUser::class, 'booking_user_id');
    }
}