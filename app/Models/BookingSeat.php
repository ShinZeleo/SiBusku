<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'trip_id',
        'seat_number',
        'seat_price',
    ];

    protected $casts = [
        'seat_price' => 'decimal:2',
    ];

    // Relasi: BookingSeat dimiliki oleh satu Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi: BookingSeat dimiliki oleh satu Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Scope: Ambil kursi yang sudah dibooking untuk trip tertentu
     */
    public function scopeForTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    /**
     * Scope: Ambil kursi yang sudah dibooking dan belum dibatalkan
     */
    public function scopeActive($query)
    {
        return $query->whereHas('booking', function ($q) {
            $q->whereNotIn('status', ['cancelled']);
        });
    }
}
