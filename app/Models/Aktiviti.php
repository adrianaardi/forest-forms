<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktiviti extends Model
{
    protected $fillable = [
        'nama_aktiviti',
        'tarikh',
        'seksyen_unit',
        'bahagian_id'
    ];
}
