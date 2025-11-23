<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event yang di-trigger ketika booking baru dibuat
 *
 * Event ini di-dispatch oleh BookingService setelah booking berhasil dibuat.
 * Event ini akan ditangkap oleh listener SendBookingNotification yang akan
 * mengirim notifikasi WhatsApp ke user dan admin.
 *
 * Event-driven architecture ini memisahkan business logic (pembuatan booking)
 * dengan side effects (notifikasi), sehingga kode lebih maintainable.
 *
 * @package App\Events
 */
class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Constructor
     *
     * @param Booking $booking Booking yang baru dibuat
     */
    public function __construct(
        public Booking $booking
    ) {}
}
