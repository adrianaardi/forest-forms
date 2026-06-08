<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahagian extends Model {
    protected $fillable = ['nama'];

    public function users() { return $this->hasMany(User::class); }
    public function pegawais() { return $this->hasMany(Pegawai::class); }
    public function aktivitis() { return $this->hasMany(Aktiviti::class); }
}