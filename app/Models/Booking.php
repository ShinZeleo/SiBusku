<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'customer_name',
        'customer_phone',
        'seats_count',
        'selected_seats',
        'total_price',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Relasi: Booking dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Booking dimiliki oleh satu Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Relasi: Booking memiliki banyak WhatsAppLog
    public function whatsappLogs()
    {
        return $this->hasMany(WhatsAppLog::class);
    }

    // Relasi: Log WhatsApp terbaru
    public function latestWhatsappLog()
    {
        return $this->hasOne(WhatsAppLog::class)->latestOfMany();
    }

    // Relasi: Booking memiliki banyak BookingSeat
    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    // Relasi: Booking memiliki banyak BookingStatusLog
    public function statusLogs()
    {
        return $this->hasMany(BookingStatusLog::class)->latest();
    }

    // Accessor: Format harga total
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_price, 0, ',', '.');
    }

    // Accessor: Format status menjadi Indonesia
    public function getStatusInIndonesianAttribute()
    {
        $statusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai'
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Get customer name - always from user for consistency
     * Falls back to stored customer_name for legacy data
     */
    public function getCustomerNameAttribute($value)
    {
        // Always use current user name if user exists
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->name;
        }

        // Fallback to stored value for legacy data or when user not loaded
        return $value ?? $this->attributes['customer_name'] ?? null;
    }

    /**
     * Get customer phone - always from user for consistency
     * Falls back to stored customer_phone for legacy data
     */
    public function getCustomerPhoneAttribute($value)
    {
        // Always use current user phone if user exists
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->phone;
        }

        // Fallback to stored value for legacy data or when user not loaded
        return $value ?? $this->attributes['customer_phone'] ?? null;
    }

    /**
     * Get WhatsApp number for notifications
     * Always uses current user phone for consistency
     */
    public function getWhatsAppNumber(): string
    {
        // Always use current user phone
        if ($this->user) {
            return $this->user->phone;
        }

        // Fallback for edge cases
        return $this->attributes['customer_phone'] ?? '';
    }
}
