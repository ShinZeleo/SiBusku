<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\WhatsAppLog;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function send(string $phone, string $message): bool
    {
        $url         = Config::get('services.fonnte.url');
        $token       = Config::get('services.fonnte.token');
        $countryCode = Config::get('services.fonnte.country_code', '62');

        // Normalisasi nomor: buang karakter non angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $payload = [
            'target'      => $phone,
            'message'     => $message,
            'countryCode' => $countryCode,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asMultipart()->post($url, $payload);

            // Simpan log ke database terlepas dari apakah pengiriman berhasil atau tidak
            // Tapi kita buat try-catch sendiri untuk menyimpan log agar tidak error keseluruhan
            try {
                $status = $response->successful() ? 'sent' : 'failed';

                WhatsAppLog::create([
                    'phone' => $phone,
                    'message' => $message,
                    'status' => $status,
                    'sent_at' => $status === 'sent' ? now() : null,
                ]);
            } catch (\Throwable $logException) {
                // Jika gagal membuat log, minimal kita logging error ini
                Log::error('Failed to create WhatsApp log: ' . $logException->getMessage());
            }

            if ($response->failed()) {
                Log::error('Fonnte send failed', [
                    'phone' => $phone,
                    'status_code' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            // Log error utama
            Log::error('Fonnte exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            // Coba buat log ke database
            try {
                WhatsAppLog::create([
                    'phone' => $phone,
                    'message' => $message,
                    'status' => 'failed',
                ]);
            } catch (\Throwable $logException) {
                // Jika gagal membuat log, minimal kita logging error ini
                Log::error('Failed to create WhatsApp log: ' . $logException->getMessage());
            }

            return false;
        }
    }

    public static function notifyBookingCreated($booking): void
    {
        $trip  = $booking->trip;
        $route = $trip->route;

        $userMessage =
            "Halo {$booking->customer_name}, booking tiket bus kamu sudah tercatat.\n\n" .
            "Rute: {$route->origin_city} → {$route->destination_city}\n" .
            "Tanggal: {$trip->departure_date}\n" .
            "Jam: {$trip->departure_time}\n" .
            "Jumlah kursi: {$booking->seats_count}\n" .
            "Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n" .
            "Status: {$booking->status}";

        self::send($booking->customer_phone, $userMessage);

        $adminPhone = Config::get('services.fonnte.admin_phone');

        if ($adminPhone) {
            $adminMessage =
                "Booking baru.\n\n" .
                "ID: {$booking->id}\n" .
                "Nama: {$booking->customer_name}\n" .
                "Rute: {$route->origin_city} → {$route->destination_city}\n" .
                "Tanggal: {$trip->departure_date}\n" .
                "Kursi: {$booking->seats_count}\n" .
                "Total: Rp " . number_format($booking->total_price, 0, ',', '.');

            self::send($adminPhone, $adminMessage);
        }
    }

    public static function notifyBookingConfirmed($booking): void
    {
        $trip  = $booking->trip;
        $route = $trip->route;

        $message =
            "Halo {$booking->customer_name}, booking tiket bus kamu SUDAH DIKONFIRMASI.\n\n" .
            "Kode booking: {$booking->id}\n" .
            "Rute: {$route->origin_city} → {$route->destination_city}\n" .
            "Tanggal: {$trip->departure_date}\n" .
            "Jam: {$trip->departure_time}\n" .
            "Kursi: {$booking->seats_count}\n" .
            "Status: {$booking->status}";

        self::send($booking->customer_phone, $message);
    }
}