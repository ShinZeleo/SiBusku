<?php

namespace App\Listeners;

use App\Events\BookingStatusUpdated;
use App\Models\BookingStatusLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogBookingStatusChange
{
    /**
     * Handle the event.
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
