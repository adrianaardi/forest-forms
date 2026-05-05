<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    protected $table = 'booking_requests';

    protected $fillable = [
        'user_id', 'bilik_id', 'tajuk_mesyuarat',
        'tarikh', 'masa_mula', 'masa_tamat',
        'status', 'cancel_token',
    ];

    public function user()
    {
        return $this->belongsTo(BookingUser::class, 'user_id');
    }

    public function bilik()
    {
        return $this->belongsTo(BookingBilik::class, 'bilik_id');
    }
}