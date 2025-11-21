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
