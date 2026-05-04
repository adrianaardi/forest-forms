<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BookingUser extends Authenticatable
{
    protected $table = 'booking_users';

    protected $fillable = ['name', 'email', 'password', 'bahagian', 'status'];

    protected $hidden = ['password'];

    public function bookings()
    {
        return $this->hasMany(BookingRequest::class, 'user_id');
    }
}