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

    // Accessor: Format harga total
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
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
}
