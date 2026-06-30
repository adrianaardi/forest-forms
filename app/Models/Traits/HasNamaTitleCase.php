<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasNamaTitleCase
{
    public function getNamaAttribute($value)
    {
        return $value !== null ? Str::title(Str::lower($value)) : $value;
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value !== null ? Str::title(Str::lower($value)) : $value;
    }
}
