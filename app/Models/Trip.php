<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk Trip (Jadwal perjalanan bus)
 *
 * Model ini merepresentasikan jadwal perjalanan bus dari satu rute.
 * Setiap trip memiliki:
 * - Route (rute perjalanan)
 * - Bus yang digunakan
 * - Tanggal dan jam keberangkatan
 * - Harga per kursi
 * - Total dan available seats
 * - Status trip
 *
 * Status yang tersedia:
 * - scheduled: Terjadwal (bisa dibooking)
 * - cancelled: Dibatalkan
 * - completed: Selesai (sudah berangkat)
 *
 * @package App\Models
 */
class Trip extends Model
{
    use HasFactory;

    /**
     * Field yang bisa diisi secara mass assignment
     *
     * @var array<string>
     */
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

    /**
     * Casting attribute ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'departure_date' => 'date',
        'departure_time' => 'datetime:H:i',
    ];

    /**
     * Relasi: Trip dimiliki oleh satu Route
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Relasi: Trip dimiliki oleh satu Bus
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Relasi: Trip memiliki banyak Booking
     *
     * Satu trip bisa memiliki banyak booking dari berbagai user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Query Scope: Filter trip yang aktif (status = 'scheduled')
     *
     * Usage: Trip::active()->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Query Scope: Filter trip berdasarkan route tertentu
     *
     * Usage: Trip::byRoute($routeId)->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $routeId ID route yang akan difilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRoute($query, $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    /**
     * Query Scope: Filter trip yang akan datang
     *
     * Filter trip dengan:
     * - departure_date >= hari ini
     * - status = 'scheduled'
     *
     * Usage: Trip::upcoming()->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('departure_date', '>=', now()->format('Y-m-d'))
                    ->where('status', 'scheduled');
    }

    /**
     * Accessor: Format harga per kursi menjadi format Rupiah
     *
     * Contoh: 150000 -> "Rp 150.000"
     *
     * Usage: $trip->price_formatted
     *
     * @return string Harga yang sudah diformat
     */
    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Accessor: Format tanggal keberangkatan ke format Indonesia
     *
     * Contoh: 2025-11-23 -> "23 Nov 2025"
     *
     * Usage: $trip->departure_date_formatted
     *
     * @return string Tanggal yang sudah diformat
     */
    public function getDepartureDateFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->departure_date)->format('d M Y');
    }

    /**
     * Relasi: Trip memiliki banyak BookingSeat
     *
     * Relasi ini menyimpan semua kursi yang sudah dibooking untuk trip ini.
     * Digunakan untuk tracking kursi mana yang sudah terisi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    /**
     * Accessor: Ambil daftar nomor kursi yang sudah dibooking
     *
     * Fungsi ini mengembalikan array nomor kursi yang sudah dibooking
     * untuk trip ini. Hanya mengambil kursi dengan status aktif.
     *
     * Digunakan untuk:
     * - Validasi kursi saat booking (mencegah double booking)
     * - Menampilkan kursi yang sudah terisi di seat picker
     *
     * Usage: $trip->booked_seats (akan return array seperti ['A1', 'A2', 'B3'])
     *
     * @return array<int, string> Array nomor kursi yang sudah dibooking
     */
    public function getBookedSeatsAttribute()
    {
        return $this->bookingSeats()
            ->active()
            ->pluck('seat_number')
            ->toArray();
    }

    /**
     * Accessor: Ambil daftar nomor kursi yang tersedia
     *
     * Fungsi ini menghitung kursi yang tersedia dengan cara:
     * 1. Ambil semua kursi dari layout bus (generateSeatNumbers)
     * 2. Kurangi dengan kursi yang sudah dibooking (booked_seats)
     * 3. Return array kursi yang tersedia
     *
     * Usage: $trip->available_seat_numbers (akan return array seperti ['A3', 'A4', 'B1'])
     *
     * @return array<int, string> Array nomor kursi yang tersedia
     */
    public function getAvailableSeatNumbersAttribute()
    {
        $bookedSeats = $this->booked_seats;
        $allSeats = $this->generateSeatNumbers();

        return array_diff($allSeats, $bookedSeats);
    }

    /**
     * Generate semua nomor kursi untuk trip ini
     *
     * Fungsi ini mengembalikan semua nomor kursi yang valid untuk trip ini.
     * Prioritas:
     * 1. Ambil dari layout bus (BusSeat) jika bus sudah punya layout
     * 2. Fallback: Generate default layout berdasarkan total_seats
     *    (format: A1, A2, A3, A4, B1, B2, ... dengan 4 kolom per baris)
     *
     * Digunakan untuk:
     * - Menampilkan semua kursi di seat picker
     * - Menghitung kursi yang tersedia
     * - Validasi nomor kursi yang dipilih
     *
     * @return array<int, string> Array semua nomor kursi (contoh: ['A1', 'A2', ..., 'H4'])
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
     * Ambil layout kursi dengan informasi lengkap untuk seat picker
     *
     * Fungsi ini mengembalikan layout kursi dengan informasi:
     * - seat_number: Nomor kursi (contoh: 'A1')
     * - row_index: Index baris (0-based)
     * - col_index: Index kolom (0-based)
     * - section: Section kursi (front, middle, back) atau null
     *
     * Prioritas:
     * 1. Ambil dari layout bus (BusSeat) jika bus sudah punya layout
     * 2. Fallback: Generate default layout dengan 4 kolom per baris
     *
     * Digunakan oleh:
     * - SeatController untuk API endpoint getSeats()
     * - Frontend untuk rendering seat picker dengan grid layout
     *
     * @return array<int, array<string, mixed>> Array layout kursi dengan informasi lengkap
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
