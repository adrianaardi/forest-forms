<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BookingUser extends Authenticatable
{
    protected $table = 'booking_users';

    protected $hidden = ['password'];

    protected $fillable = ['name', 'email', 'password', 'bahagian', 'phone', 'wilayah_id', 'status'];

    public function wilayah()
    {
        return $this->belongsTo(\App\Models\Wilayah::class, 'wilayah_id');
    }
    
    public function bookings()
    {
        return $this->hasMany(BookingRequest::class, 'user_id');
    }
}
