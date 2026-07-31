<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BookingUser extends Authenticatable
{
    protected $table = 'booking_users';

    protected $hidden = ['password'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'bahagian',
        'jawatan',
        'phone',
        'wilayah_id',
        'status',
        'can_book',
        'signature',
        'email_verified_at',
        'email_verification_token',
    ];

    protected $casts = [
        'can_book' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function wilayah()
    {
        return $this->belongsTo(\App\Models\Wilayah::class, 'wilayah_id');
    }
    
    public function bookings()
    {
        return $this->hasMany(BookingRequest::class, 'user_id');
    }
}
