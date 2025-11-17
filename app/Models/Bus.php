<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate_number',
        'capacity',
        'bus_class',
        'is_active',
    ];

    // Relasi: Bus memiliki banyak Trip
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
