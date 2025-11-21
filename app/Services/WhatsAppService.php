<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\WhatsAppLog;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp message via Fonnte API
     *
     * @param string $phone Phone number (will be normalized)
     * @param string $message Message content
     * @param Booking|null $booking Related booking (for logging)
     * @param int $retryAttempts Number of retry attempts on failure
     * @return bool Success status
     */
    public static function send(
        string $phone,
        string $message,
        ?Booking $booking = null,
        int $retryAttempts = 2
    ): bool {
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

        // Normalisasi nomor: buang karakter non angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika nomor dimulai dengan 0, ganti dengan country code
        if (str_starts_with($phone, '0')) {
            $phone = $countryCode . substr($phone, 1);
        }

        // Format payload sesuai dokumentasi Fonnte
        $payload = [
            'target' => $phone,
            'message' => $message,
        ];

        // Jika perlu country code, tambahkan
        if ($countryCode && $countryCode !== '62') {
            $payload['countryCode'] = $countryCode;
        }

        $attempt = 0;
        $lastError = null;

        while ($attempt <= $retryAttempts) {
            try {
                // Coba dengan JSON dulu (lebih umum untuk API modern)
                $response = Http::timeout(15)
                    ->withHeaders([
                        'Authorization' => $token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, $payload);

                // Jika gagal, coba dengan multipart (fallback)
                if (!$response->successful() && $attempt === 0) {
                    $response = Http::timeout(15)
                        ->withHeaders([
                            'Authorization' => $token,
                        ])
                        ->asMultipart()
                        ->post($url, $payload);
                }

                $success = $response->successful();
                $status = $success ? 'sent' : 'failed';

                // Log ke database
                $log = self::logToDatabase(
                    $phone,
                    $message,
                    $status,
                    $booking,
                    $success ? null : $response->body()
                );

                if ($success) {
                    Log::info('WhatsApp sent successfully', [
                        'phone' => $phone,
                        'booking_id' => $booking?->id,
                        'log_id' => $log?->id,
                    ]);
                    return true;
                }

                // Jika gagal dan masih ada retry, tunggu sebentar
                if ($attempt < $retryAttempts) {
                    $lastError = $response->body();
                    Log::warning('WhatsApp send failed, retrying', [
                        'phone' => $phone,
                        'attempt' => $attempt + 1,
                        'response' => $lastError,
                    ]);
                    sleep(1); // Tunggu 1 detik sebelum retry
                } else {
                    $lastError = $response->body();
                    Log::error('WhatsApp send failed after retries', [
                        'phone' => $phone,
                        'attempts' => $attempt + 1,
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

                // Jika masih ada retry, coba lagi
                if ($attempt < $retryAttempts) {
                    sleep(1);
                } else {
                    // Log ke database dengan status failed
                    self::logToDatabase(
                        $phone,
                        $message,
                        'failed',
                        $booking,
                        $lastError
                    );
                }
            }

            $attempt++;
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
        try {
            $trip = $booking->trip;
            $route = $trip->route;
            $seatNumbers = $booking->bookingSeats->pluck('seat_number')->join(', ') ?: $booking->selected_seats;

            $userMessage =
                "Halo {$booking->customer_name}, booking tiket bus kamu sudah tercatat.\n\n" .
                "Kode Booking: #{$booking->id}\n" .
                "Rute: {$route->origin_city} → {$route->destination_city}\n" .
                "Tanggal: " . \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') . "\n" .
                "Jam: " . $trip->departure_time . "\n" .
                "Kursi: {$seatNumbers}\n" .
                "Jumlah: {$booking->seats_count} kursi\n" .
                "Total: Rp " . number_format((float) $booking->total_price, 0, ',', '.') . "\n\n" .
                "Status: Menunggu Konfirmasi\n\n" .
                "Terima kasih telah menggunakan SIBUSKU!";

            self::send($booking->customer_phone, $userMessage, $booking);

            // Notify admin
            $adminPhone = Config::get('services.fonnte.admin_phone');
            if ($adminPhone) {
                $adminMessage =
                    "📋 Booking Baru\n\n" .
                    "ID: #{$booking->id}\n" .
                    "Nama: {$booking->customer_name}\n" .
                    "HP: {$booking->customer_phone}\n" .
                    "Rute: {$route->origin_city} → {$route->destination_city}\n" .
                    "Tanggal: " . \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') . "\n" .
                    "Jam: " . $trip->departure_time . "\n" .
                    "Kursi: {$seatNumbers}\n" .
                    "Total: Rp " . number_format((float) $booking->total_price, 0, ',', '.');

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

            $message =
                "✅ Booking Dikonfirmasi!\n\n" .
                "Halo {$booking->customer_name},\n\n" .
                "Booking tiket bus kamu SUDAH DIKONFIRMASI.\n\n" .
                "Kode Booking: #{$booking->id}\n" .
                "Rute: {$route->origin_city} → {$route->destination_city}\n" .
                "Tanggal: " . \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') . "\n" .
                "Jam: " . $trip->departure_time . "\n" .
                "Kursi: {$seatNumbers}\n" .
                "Status: Dikonfirmasi\n\n" .
                "Selamat jalan! 🚌";

            self::send($booking->customer_phone, $message, $booking);
        } catch (\Throwable $e) {
            Log::error('Error in notifyBookingConfirmed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
