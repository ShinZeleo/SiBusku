<?php

namespace App\Listeners;

use App\Events\BookingStatusUpdated;
use App\Models\BookingStatusLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Listener untuk event BookingStatusUpdated
 *
 * Listener ini menangkap event BookingStatusUpdated dan membuat log
 * perubahan status ke database (BookingStatusLog) untuk audit trail.
 *
 * Log ini berguna untuk:
 * - Tracking history perubahan status booking
 * - Audit trail (siapa yang mengubah status, kapan, dan kenapa)
 * - Debugging masalah terkait perubahan status
 *
 * @package App\Listeners
 */
class LogBookingStatusChange
{
    /**
     * Menangani event BookingStatusUpdated
     *
     * Fungsi ini dipanggil otomatis oleh Laravel ketika event BookingStatusUpdated
     * di-dispatch. Fungsi ini akan:
     * 1. Ambil user yang melakukan perubahan (Auth::id() atau booking->user_id sebagai fallback)
     * 2. Buat record BookingStatusLog dengan informasi:
     *    - booking_id: ID booking yang statusnya berubah
     *    - user_id: ID user yang melakukan perubahan
     *    - status_lama: Status sebelum perubahan
     *    - status_baru: Status setelah perubahan
     *    - keterangan: Keterangan perubahan (dari event atau auto-generated)
     * 3. Log hasil ke application log
     *
     * @param BookingStatusUpdated $event Event yang berisi informasi perubahan status
     * @return void
     */
    public function handle(BookingStatusUpdated $event): void
    {
        $booking = $event->booking;
        $userId = Auth::id() ?? $booking->user_id; // Fallback jika tidak ada user (system)

        try {
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'user_id' => $userId,
                'status_lama' => $event->oldStatus,
                'status_baru' => $event->newStatus,
                'keterangan' => $event->keterangan ??
                    "Status diubah dari {$event->oldStatus} menjadi {$event->newStatus}",
            ]);

            Log::info('Booking status log created', [
                'booking_id' => $booking->id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to create booking status log', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
