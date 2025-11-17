<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_city',
        'destination_city',
        'duration_estimate',
        'is_active',
    ];

    // Relasi: Route memiliki banyak Trip
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
