<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingBilik extends Model
{
    protected $table = 'booking_bilik';

    protected $fillable = ['nama_bilik', 'aras', 'lokasi', 'kapasiti'];

    public function bookings()
    {
        return $this->hasMany(BookingRequest::class, 'bilik_id');
    }
}