<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahagianSupervisor extends Model
{
    protected $table = 'bahagian_supervisors';

    protected $fillable = [
        'nama_bahagian',
        'email_supervisor',
    ];

    public function permohonan()
    {
        return $this->hasMany(BorangMuatNaikBahan::class, 'bahagian_id');
    }
}