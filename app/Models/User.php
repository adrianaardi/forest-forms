<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\BrevoMailer;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'wilayah_id',
        'bahagian_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($this->email));

        BrevoMailer::send(
            $this->email,
            $this->name,
            'Reset Kata Laluan — Sistem Forest Forms',
            view('emails.password-reset', ['user' => $this, 'resetUrl' => $resetUrl])->render()
        );
    }

    public function wilayah()
    {
        return $this->belongsTo(\App\Models\Wilayah::class);
    }

    public function bahagian() 
    { 
        return $this->belongsTo(Bahagian::class); 
    }
}
