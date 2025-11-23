<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

/**
 * Listener untuk event BookingCreated
 *
 * Listener ini menangkap event BookingCreated dan mengirim notifikasi
 * WhatsApp ke user dan admin ketika booking baru dibuat.
 *
 * Duplicate Prevention:
 * - Cek apakah sudah ada log WhatsApp 'sent' untuk booking ini dalam 1 menit terakhir
 * - Jika ada, skip pengiriman (mencegah double notification)
 *
 * Error Handling:
 * - Jika pengiriman gagal, error akan di-log tapi tidak akan mengganggu
 *   proses pembuatan booking (karena ini side effect)
 *
 * @package App\Listeners
 */
class SendBookingNotification
{
    /**
     * Menangani event BookingCreated
     *
     * Fungsi ini dipanggil otomatis oleh Laravel ketika event BookingCreated
     * di-dispatch. Fungsi ini akan:
     * 1. Cek duplicate notification (dalam 1 menit terakhir)
     * 2. Panggil WhatsAppService::notifyBookingCreated() untuk kirim notifikasi
     * 3. Log hasil pengiriman (success atau error)
     *
     * @param BookingCreated $event Event yang berisi booking yang baru dibuat
     * @return void
     */
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;

        // Cegah double send dengan cek apakah sudah ada log untuk booking ini
        $existingLog = \App\Models\WhatsAppLog::where('booking_id', $booking->id)
            ->where('status', 'sent')
            ->where('created_at', '>=', now()->subMinute())
            ->first();

        if ($existingLog) {
            Log::warning('WhatsApp notification already sent, skipping duplicate', [
                'booking_id' => $booking->id,
                'existing_log_id' => $existingLog->id,
            ]);
            return;
        }

        try {
            WhatsAppService::notifyBookingCreated($booking);
            Log::info('WhatsApp notification sent', [
                'booking_id' => $booking->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp notification failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
