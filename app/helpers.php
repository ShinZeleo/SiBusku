<?php

use App\Models\Booking;

if (! function_exists('booking_whatsapp_badge')) {
    /**
     * Determine WhatsApp status badge meta for a booking.
     */
    function booking_whatsapp_badge(?Booking $booking): array
    {
        $sent = false;

        if ($booking instanceof Booking) {
            $attributes = $booking->getAttributes();

            if (array_key_exists('wa_sent', $attributes)) {
                $sent = (bool) $booking->getAttribute('wa_sent');
            } elseif ($booking->relationLoaded('whatsappLogs')) {
                $sent = $booking->whatsappLogs->isNotEmpty();
            } elseif ($booking->relationLoaded('whatsappLog')) {
                $sent = ! is_null($booking->whatsappLog);
            } elseif (method_exists($booking, 'whatsappLogs')) {
                $sent = $booking->whatsappLogs()->exists();
            } elseif (method_exists($booking, 'whatsappLog')) {
                $sent = $booking->whatsappLog()->exists();
            }
        }

        return [
            'sent' => $sent,
            'label' => $sent ? 'WA terkirim' : 'WA pending',
            'classes' => $sent ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800',
            'dot' => $sent ? 'bg-green-500' : 'bg-yellow-500',
        ];
    }
}
