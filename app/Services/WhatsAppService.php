<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\WhatsAppLog;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WhatsAppService
{
    /**
     * Send WhatsApp message via Fonnte API
     */
    public static function send(
        string $phone,
        string $message,
        ?Booking $booking = null,
        int $retryAttempts = 2
    ): bool {
        // Cegah double send dengan cache lock (5 detik)
        $cacheKey = 'whatsapp_send_' . md5($phone . $message . ($booking?->id ?? ''));
        if (Cache::has($cacheKey)) {
            Log::warning('WhatsApp send prevented (duplicate within 5 seconds)', [
                'phone' => $phone,
                'booking_id' => $booking?->id,
            ]);
            return false;
        }
        Cache::put($cacheKey, true, 5); // Lock selama 5 detik

        if (!Config::get('services.fonnte.enabled', true)) {
            Log::info('WhatsApp service disabled, skipping send', [
                'phone' => $phone,
                'booking_id' => $booking?->id,
            ]);
            self::logToDatabase($phone, $message, 'pending', $booking, 'Service disabled');
            return true;
        }

        $url = Config::get('services.fonnte.url');
        $token = Config::get('services.fonnte.token');
        $countryCode = Config::get('services.fonnte.country_code', '62');

        if (!$url || !$token) {
            Log::error('WhatsApp service not configured', [
                'url_set' => !empty($url),
                'token_set' => !empty($token),
            ]);

            self::logToDatabase($phone, $message, 'failed', $booking, 'Service not configured');
            return false;
        }

        $timeout = Config::get('services.fonnte.timeout', 3);

        // Normalisasi nomor
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = $countryCode . substr($phone, 1);
        }

        // Format payload
        $payload = [
            'target' => $phone,
            'message' => $message,
        ];

        if ($countryCode && $countryCode !== '62') {
            $payload['countryCode'] = $countryCode;
        }

        $attempt = 0;
        $lastError = null;
        $sent = false;

        while ($attempt <= $retryAttempts && !$sent) {
            try {
                // Coba dengan JSON
                $response = Http::timeout($timeout)
                    ->withHeaders([
                        'Authorization' => $token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, $payload);

                // Jika gagal dan attempt pertama, coba dengan multipart (fallback)
                // HANYA jika response pertama tidak berhasil
                if (!$response->successful() && $attempt === 0) {
                    $response = Http::timeout($timeout)
                        ->withHeaders([
                            'Authorization' => $token,
                        ])
                        ->asMultipart()
                        ->post($url, $payload);
                }

                $success = $response->successful();

                if ($success) {
                    // Hanya log sekali jika berhasil
                    $log = self::logToDatabase(
                        $phone,
                        $message,
                        'sent',
                        $booking,
                        null
                    );
                    Log::info('WhatsApp sent successfully', [
                        'phone' => $phone,
                        'booking_id' => $booking?->id,
                        'log_id' => $log?->id,
                        'attempt' => $attempt + 1,
                    ]);
                    $sent = true;
                    return true;
                }

                // Jika gagal dan ini attempt terakhir, log sebagai failed
                if ($attempt >= $retryAttempts) {
                    $lastError = $response->body();
                    self::logToDatabase(
                        $phone,
                        $message,
                        'failed',
                        $booking,
                        $lastError
                    );
                    Log::error('WhatsApp send failed after retries', [
                        'phone' => $phone,
                        'attempts' => $attempt + 1,
                        'response' => $lastError,
                    ]);
                } else {
                    $lastError = $response->body();
                    Log::warning('WhatsApp send failed, retrying', [
                        'phone' => $phone,
                        'attempt' => $attempt + 1,
                        'response' => $lastError,
                    ]);
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::error('WhatsApp exception', [
                    'phone' => $phone,
                    'attempt' => $attempt + 1,
                    'error' => $lastError,
                ]);

                // Jika ini attempt terakhir, log sebagai failed
                if ($attempt >= $retryAttempts) {
                    self::logToDatabase(
                        $phone,
                        $message,
                        'failed',
                        $booking,
                        $lastError
                    );
                }
            }

            if (!$sent) {
                $attempt++;
            }
        }

        return false;
    }

    /**
     * Log WhatsApp message to database
     */
    private static function logToDatabase(
        string $phone,
        string $message,
        string $status,
        ?Booking $booking = null,
        ?string $errorMessage = null
    ): ?WhatsAppLog {
        try {
            return WhatsAppLog::create([
                'booking_id' => $booking?->id,
                'phone' => $phone,
                'message' => $message,
                'status' => $status,
                'sent_at' => $status === 'sent' ? now() : null,
                'error_message' => $errorMessage,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to create WhatsApp log', [
                'error' => $e->getMessage(),
                'phone' => $phone,
                'booking_id' => $booking?->id,
            ]);
            return null;
        }
    }

    /**
     * Notify user when booking is created
     */
    public static function notifyBookingCreated(Booking $booking): void
    {
        // Cegah double send dengan cek apakah sudah ada log untuk booking ini dalam 1 menit terakhir
        $existingLog = WhatsAppLog::where('booking_id', $booking->id)
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
            $trip = $booking->trip;
            $route = $trip->route;
            $seatNumbers = $booking->bookingSeats->pluck('seat_number')->join(', ') ?: $booking->selected_seats;

            $customerName = $booking->customer_name;

            // Format tanggal dan jam dengan benar
            $departureDate = \Carbon\Carbon::parse($trip->departure_date)->format('d M Y');
            $departureTime = $trip->departure_time;
            // Jika departure_time adalah datetime, ambil hanya jamnya
            if (strlen($departureTime) > 5) {
                $departureTime = \Carbon\Carbon::parse($trip->departure_time)->format('H:i');
            }

            $userMessage =
                "Halo {$customerName}, booking tiket bus kamu sudah tercatat.\n\n" .
                "📋 Kode Booking: #{$booking->id}\n" .
                "📍 Rute: {$route->origin_city} → {$route->destination_city}\n" .
                "📅 Tanggal: {$departureDate}\n" .
                "🕐 Jam: {$departureTime}\n" .
                "💺 Kursi: {$seatNumbers}\n" .
                "👥 Jumlah: {$booking->seats_count} kursi\n" .
                "💰 Total: Rp " . number_format((float) $booking->total_price, 0, ',', '.') . "\n\n" .
                "⏳ Status: Menunggu Konfirmasi\n\n" .
                "Terima kasih telah menggunakan SIBUSKU! 🚌";

            // Kirim ke user (hanya sekali)
            self::send($booking->getWhatsAppNumber(), $userMessage, $booking);

            // Notify admin (opsional)
            $adminPhone = Config::get('services.fonnte.admin_phone');
            if ($adminPhone) {
                // Format tanggal dan jam untuk admin
                $departureDate = \Carbon\Carbon::parse($trip->departure_date)->format('d M Y');
                $departureTime = $trip->departure_time;
                if (strlen($departureTime) > 5) {
                    $departureTime = \Carbon\Carbon::parse($trip->departure_time)->format('H:i');
                }

                $adminMessage =
                    "📋 Booking Baru\n\n" .
                    "ID: #{$booking->id}\n" .
                    "👤 Nama: {$booking->customer_name}\n" .
                    "📱 HP: {$booking->customer_phone}\n" .
                    "📍 Rute: {$route->origin_city} → {$route->destination_city}\n" .
                    "📅 Tanggal: {$departureDate}\n" .
                    "🕐 Jam: {$departureTime}\n" .
                    "💺 Kursi: {$seatNumbers}\n" .
                    "💰 Total: Rp " . number_format((float) $booking->total_price, 0, ',', '.');

                self::send($adminPhone, $adminMessage, $booking);
            }
        } catch (\Throwable $e) {
            Log::error('Error in notifyBookingCreated', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify user when booking is confirmed
     */
    public static function notifyBookingConfirmed(Booking $booking): void
    {
        try {
            $trip = $booking->trip;
            $route = $trip->route;
            $seatNumbers = $booking->bookingSeats->pluck('seat_number')->join(', ') ?: $booking->selected_seats;

            $customerName = $booking->customer_name;

            // Format tanggal dan jam dengan benar
            $departureDate = \Carbon\Carbon::parse($trip->departure_date)->format('d M Y');
            $departureTime = $trip->departure_time;
            if (strlen($departureTime) > 5) {
                $departureTime = \Carbon\Carbon::parse($trip->departure_time)->format('H:i');
            }

            $message =
                "✅ Booking Dikonfirmasi!\n\n" .
                "Halo {$customerName},\n\n" .
                "Booking tiket bus kamu SUDAH DIKONFIRMASI.\n\n" .
                "📋 Kode Booking: #{$booking->id}\n" .
                "📍 Rute: {$route->origin_city} → {$route->destination_city}\n" .
                "📅 Tanggal: {$departureDate}\n" .
                "🕐 Jam: {$departureTime}\n" .
                "💺 Kursi: {$seatNumbers}\n" .
                "✅ Status: Dikonfirmasi\n\n" .
                "Selamat jalan! 🚌";

            self::send($booking->getWhatsAppNumber(), $message, $booking);
        } catch (\Throwable $e) {
            Log::error('Error in notifyBookingConfirmed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
