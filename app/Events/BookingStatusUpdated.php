<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event yang di-trigger ketika status booking diupdate
 *
 * Event ini di-dispatch oleh BookingService ketika status booking berubah
 * (misalnya dari 'pending' menjadi 'confirmed' atau 'cancelled').
 *
 * Event ini akan ditangkap oleh listener LogBookingStatusChange yang akan
 * membuat log perubahan status ke database (BookingStatusLog) untuk audit trail.
 *
 * Data yang dibawa:
 * - booking: Booking yang statusnya berubah
 * - oldStatus: Status lama
 * - newStatus: Status baru
 * - keterangan: Keterangan perubahan (optional)
 *
 * @package App\Events
 */
class BookingStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Constructor
     *
     * @param Booking $booking Booking yang statusnya berubah
     * @param string $oldStatus Status lama (pending, confirmed, cancelled, completed)
     * @param string $newStatus Status baru (pending, confirmed, cancelled, completed)
     * @param string|null $keterangan Keterangan perubahan status (optional)
     */
    public function __construct(
        public Booking $booking,
        public string $oldStatus,
        public string $newStatus,
        public ?string $keterangan = null
    ) {}
}
