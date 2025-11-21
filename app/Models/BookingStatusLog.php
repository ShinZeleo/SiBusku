<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'status_lama',
        'status_baru',
        'keterangan',
    ];

    // Relasi: BookingStatusLog dimiliki oleh satu Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi: BookingStatusLog dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Ambil log untuk booking tertentu
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: Urutkan dari terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
