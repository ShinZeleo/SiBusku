<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk Booking (Pemesanan tiket bus)
 *
 * Model ini merepresentasikan pemesanan tiket bus oleh user.
 * Setiap booking memiliki:
 * - User yang melakukan booking
 * - Trip yang dibooking
 * - Kursi yang dipilih
 * - Status booking dan pembayaran
 * - Log WhatsApp dan status
 *
 * Status yang tersedia:
 * - pending: Menunggu konfirmasi
 * - confirmed: Sudah dikonfirmasi
 * - cancelled: Dibatalkan
 * - completed: Selesai (trip sudah berangkat)
 *
 * @package App\Models
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * Field yang bisa diisi secara mass assignment
     *
     * @var array<string>
     */
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

    /**
     * Casting attribute ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /**
     * Relasi: Booking dimiliki oleh satu User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Booking dimiliki oleh satu Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Relasi: Booking memiliki banyak WhatsAppLog
     *
     * Satu booking bisa memiliki banyak log WhatsApp (untuk tracking
     * semua notifikasi yang dikirim untuk booking ini).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function whatsappLogs()
    {
        return $this->hasMany(WhatsAppLog::class);
    }

    /**
     * Relasi: Log WhatsApp terbaru untuk booking ini
     *
     * Relasi ini mengembalikan hanya 1 log WhatsApp terbaru (latest)
     * untuk booking ini. Berguna untuk menampilkan status notifikasi
     * terbaru di UI.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestWhatsappLog()
    {
        return $this->hasOne(WhatsAppLog::class)->latestOfMany();
    }

    /**
     * Relasi: Booking memiliki banyak BookingSeat
     *
     * Satu booking bisa memiliki banyak kursi (multiple seats).
     * Relasi ini menyimpan detail setiap kursi yang dibooking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    /**
     * Relasi: Booking memiliki banyak BookingStatusLog
     *
     * Relasi ini menyimpan history perubahan status booking
     * untuk audit trail. Diurutkan dari yang terbaru.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusLogs()
    {
        return $this->hasMany(BookingStatusLog::class)->latest();
    }

    /**
     * Accessor: Format harga total menjadi format Rupiah
     *
     * Contoh: 150000 -> "Rp 150.000"
     *
     * Usage: $booking->total_price_formatted
     *
     * @return string Harga yang sudah diformat
     */
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_price, 0, ',', '.');
    }

    /**
     * Accessor: Format status booking menjadi bahasa Indonesia
     *
     * Mapping:
     * - pending -> "Menunggu"
     * - confirmed -> "Dikonfirmasi"
     * - cancelled -> "Dibatalkan"
     * - completed -> "Selesai"
     *
     * Usage: $booking->status_in_indonesian
     *
     * @return string Status dalam bahasa Indonesia
     */
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
     * Accessor: Ambil nama customer (selalu dari user untuk konsistensi)
     *
     * Accessor ini memastikan bahwa nama customer selalu diambil dari
     * relasi user jika tersedia, bukan dari field customer_name di database.
     * Ini memastikan konsistensi data jika user mengubah nama mereka.
     *
     * Fallback: Jika user tidak di-load atau tidak ada, gunakan customer_name
     * dari database (untuk backward compatibility).
     *
     * Usage: $booking->customer_name
     *
     * @param mixed $value Nilai dari database (jika ada)
     * @return string|null Nama customer
     */
    public function getCustomerNameAttribute($value)
    {
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->name;
        }
        return $value ?? $this->attributes['customer_name'] ?? null;
    }

    /**
     * Accessor: Ambil nomor telepon customer (selalu dari user untuk konsistensi)
     *
     * Accessor ini memastikan bahwa nomor telepon selalu diambil dari
     * relasi user jika tersedia, bukan dari field customer_phone di database.
     * Ini memastikan konsistensi data jika user mengubah nomor mereka.
     *
     * Fallback: Jika user tidak di-load atau tidak ada, gunakan customer_phone
     * dari database (untuk backward compatibility).
     *
     * Usage: $booking->customer_phone
     *
     * @param mixed $value Nilai dari database (jika ada)
     * @return string|null Nomor telepon customer
     */
    public function getCustomerPhoneAttribute($value)
    {
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->phone;
        }
        return $value ?? $this->attributes['customer_phone'] ?? null;
    }

    /**
     * Mendapatkan nomor WhatsApp untuk notifikasi
     *
     * Fungsi ini mengembalikan nomor WhatsApp yang akan digunakan
     * untuk mengirim notifikasi. Prioritas:
     * 1. Nomor dari relasi user (jika user di-load)
     * 2. Nomor dari field customer_phone di database
     *
     * Fungsi ini digunakan oleh WhatsAppService untuk mendapatkan
     * nomor tujuan pengiriman notifikasi.
     *
     * @return string Nomor WhatsApp (bisa kosong jika tidak ada)
     */
    public function getWhatsAppNumber(): string
    {
        if ($this->user) {
            return $this->user->phone;
        }
        return $this->attributes['customer_phone'] ?? '';
    }
}
