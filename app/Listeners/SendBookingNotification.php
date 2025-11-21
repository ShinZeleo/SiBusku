<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class SendBookingNotification
{
    /**
     * Handle the event.
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
