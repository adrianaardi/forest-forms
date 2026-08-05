<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'supervisor_id',
        'status',
        'can_book',
        'is_supervisor',
        'is_hod',
        'is_accountant',
        'signature',
        'email_verified_at',
        'email_verification_token',
    ];

    protected $casts = [
        'can_book' => 'boolean',
        'is_supervisor' => 'boolean',
        'is_hod' => 'boolean',
        'is_accountant' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function wilayah()
    {
        return $this->belongsTo(\App\Models\Wilayah::class, 'wilayah_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'supervisor_id');
    }

    public function supervisees(): HasMany
    {
        return $this->hasMany(self::class, 'supervisor_id');
    }
    
    public function bookings()
    {
        return $this->hasMany(BookingRequest::class, 'user_id');
    }

    public function kursusDicipta(): HasMany
    {
        return $this->hasMany(BookingKursus::class, 'created_by');
    }

    public function permohonanKursus(): HasMany
    {
        return $this->hasMany(BookingKursusApplication::class, 'booking_user_id');
    }
}
