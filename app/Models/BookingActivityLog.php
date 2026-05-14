<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingActivityLog extends Model
{
    protected $table = 'booking_activity_logs';
    protected $fillable = ['actor_type', 'actor_name', 'action', 'description'];

    public static function log(string $actorType, string $actorName, string $action, string $description): void
    {
        static::create([
            'actor_type'  => $actorType,
            'actor_name'  => $actorName,
            'action'      => $action,
            'description' => $description,
        ]);
    }
}