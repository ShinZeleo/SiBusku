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

    // Relasi: Bus memiliki banyak BusSeat
    public function seats()
    {
        return $this->hasMany(BusSeat::class);
    }

    /**
     * Ambil layout kursi yang aktif, diurutkan
     */
    public function getSeatLayoutAttribute()
    {
        return $this->seats()
            ->active()
            ->ordered()
            ->get()
            ->map(function ($seat) {
                return [
                    'seat_number' => $seat->seat_number,
                    'row_index' => $seat->row_index,
                    'col_index' => $seat->col_index,
                    'deck' => $seat->deck,
                    'section' => $seat->section,
                ];
            })
            ->toArray();
    }
}
