<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $fillable = ['nama_wilayah'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}