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

    // Relasi: Trip memiliki banyak BookingSeat
    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    /**
     * Ambil daftar kursi yang sudah dibooking untuk trip ini
     */
    public function getBookedSeatsAttribute()
    {
        return $this->bookingSeats()
            ->active()
            ->pluck('seat_number')
            ->toArray();
    }

    /**
     * Ambil daftar kursi yang tersedia untuk trip ini
     */
    public function getAvailableSeatNumbersAttribute()
    {
        $bookedSeats = $this->booked_seats;
        $allSeats = $this->generateSeatNumbers();

        return array_diff($allSeats, $bookedSeats);
    }

    /**
     * Generate semua nomor kursi berdasarkan layout bus atau fallback ke default
     */
    public function generateSeatNumbers()
    {
        // Coba ambil dari bus seat layout terlebih dahulu
        if ($this->bus && $this->bus->seats()->active()->exists()) {
            return $this->bus->seats()
                ->active()
                ->ordered()
                ->pluck('seat_number')
                ->toArray();
        }

        // Fallback: Generate default layout jika bus belum punya layout
        $seats = [];
        $rows = range('A', 'Z');
        $colsPerRow = 4; // Default 4 kolom per baris

        // Hitung jumlah baris yang dibutuhkan
        $totalRows = ceil($this->total_seats / $colsPerRow);

        $seatCount = 0;
        for ($row = 0; $row < $totalRows && $seatCount < $this->total_seats; $row++) {
            for ($col = 1; $col <= $colsPerRow && $seatCount < $this->total_seats; $col++) {
                $seats[] = $rows[$row] . $col;
                $seatCount++;
            }
        }

        return $seats;
    }

    /**
     * Ambil layout kursi dari bus dengan informasi row/col untuk rendering
     */
    public function getSeatLayoutForPicker()
    {
        if ($this->bus && $this->bus->seats()->active()->exists()) {
            return $this->bus->seats()
                ->active()
                ->ordered()
                ->get()
                ->map(function ($seat) {
                    return [
                        'seat_number' => $seat->seat_number,
                        'row_index' => $seat->row_index,
                        'col_index' => $seat->col_index,
                        'section' => $seat->section,
                    ];
                })
                ->toArray();
        }

        // Fallback: Generate default layout
        $layout = [];
        $rows = range('A', 'Z');
        $colsPerRow = 4;
        $totalRows = ceil($this->total_seats / $colsPerRow);

        $seatCount = 0;
        for ($row = 0; $row < $totalRows && $seatCount < $this->total_seats; $row++) {
            for ($col = 1; $col <= $colsPerRow && $seatCount < $this->total_seats; $col++) {
                $layout[] = [
                    'seat_number' => $rows[$row] . $col,
                    'row_index' => $row,
                    'col_index' => $col - 1,
                    'section' => null,
                ];
                $seatCount++;
            }
        }

        return $layout;
    }
}
