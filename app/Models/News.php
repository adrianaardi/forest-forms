<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'headline',
        'bahagian_id',
    ];

    public function bahagian()
    {
        return $this->belongsTo(Bahagian::class);
    }
}