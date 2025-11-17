<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'bus_id',
        'departure_date',
        'departure_time',
        'price',
        'total_seats',
        'available_seats',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'departure_time' => 'datetime:H:i',
    ];

    // Relasi: Trip dimiliki oleh satu Route
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // Relasi: Trip dimiliki oleh satu Bus
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    // Relasi: Trip memiliki banyak Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scope: Trip aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Scope: Trip berdasarkan route
    public function scopeByRoute($query, $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    // Scope: Trip mendatang
    public function scopeUpcoming($query)
    {
        return $query->where('departure_date', '>=', now()->format('Y-m-d'))
                    ->where('status', 'scheduled');
    }

    // Accessor: Format harga
    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Accessor: Format tanggal ke Indonesia
    public function getDepartureDateFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->departure_date)->format('d M Y');
    }
}
