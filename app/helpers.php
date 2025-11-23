<?php

use App\Models\Booking;

/**
 * Helper Functions untuk SIBUSKU
 *
 * File ini berisi fungsi-fungsi helper global yang digunakan
 * di seluruh aplikasi. Fungsi-fungsi ini di-autoload oleh Composer.
 */

if (! function_exists('booking_whatsapp_badge')) {
    /**
     * Menentukan badge status WhatsApp untuk booking
     *
     * Fungsi helper ini mengembalikan informasi badge untuk menampilkan
     * status notifikasi WhatsApp di UI. Fungsi ini fleksibel dan bisa
     * bekerja dengan berbagai cara data WhatsApp di-load:
     *
     * 1. Cek attribute 'wa_sent' langsung di model (jika ada)
     * 2. Cek relasi 'whatsappLogs' jika sudah di-load
     * 3. Cek relasi 'whatsappLog' (latest) jika sudah di-load
     * 4. Cek relasi 'whatsappLogs' via query jika belum di-load
     * 5. Cek relasi 'whatsappLog' via query jika belum di-load
     *
     * Return value:
     * - sent: bool - Apakah WA sudah terkirim
     * - label: string - Label untuk ditampilkan ('WA terkirim' atau 'WA pending')
     * - classes: string - CSS classes untuk badge (bg-green-100 atau bg-yellow-100)
     * - dot: string - CSS classes untuk dot indicator (bg-green-500 atau bg-yellow-500)
     *
     * Usage di Blade:
     * @php($waBadge = booking_whatsapp_badge($booking))
     * <span class="{{ $waBadge['classes'] }}">{{ $waBadge['label'] }}</span>
     *
     * @param Booking|null $booking Booking yang akan dicek status WhatsApp-nya
     * @return array<string, mixed> Array dengan keys: sent, label, classes, dot
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
