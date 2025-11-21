<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsAppLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_logs'; // Nama tabel yang sesuai dengan migrasi

    protected $fillable = [
        'booking_id',
        'phone',
        'message',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relasi: WhatsAppLog dimiliki oleh satu Booking (opsional)
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
