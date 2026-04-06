<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = [
        'room_name',
        'building',
        'room_type',
        'capacity',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'room_type' => 'string',
    ];

    public function scopeByType($query, $type)
    {
        return $query->where('room_type', $type);
    }

    public function scopeByBuilding($query, $building)
    {
        return $query->where('building', $building);
    }
}
