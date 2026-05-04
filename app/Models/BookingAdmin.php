<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BookingAdmin extends Authenticatable
{
    protected $table = 'booking_admins';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];
}