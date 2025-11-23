<?php

namespace App\Services;

use App\Models\BookingSeat;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengelola validasi dan assignment kursi
 *
 * Service ini menangani semua operasi yang berkaitan dengan kursi,
 * termasuk validasi kursi yang dipilih, assignment kursi ke booking,
 * dan pengecekan ketersediaan kursi.
 *
 * @package App\Services
 */
class SeatService
{
    /**
     * Validasi kursi yang dipilih oleh user
     *
     * Fungsi ini melakukan validasi lengkap terhadap kursi yang dipilih:
     * 1. Cek apakah ada kursi yang dipilih (tidak boleh kosong)
     * 2. Cek duplikat kursi (tidak boleh ada kursi yang sama dipilih 2x)
     * 3. Cek jumlah kursi tersedia (tidak boleh melebihi available_seats)
     * 4. Cek apakah kursi sudah dibooking oleh user lain
     *
     * @param Trip $trip Trip yang akan dibooking
     * @param array $selectedSeats Array nomor kursi yang dipilih (contoh: ['A1', 'A2', 'B3'])
     * @return array Array dengan struktur:
     *               - 'valid' => bool: true jika valid, false jika tidak valid
     *               - 'message' => string: pesan error jika tidak valid (optional)
     */
    public function validateSeats(Trip $trip, array $selectedSeats): array
    {
        if (empty($selectedSeats)) {
            return [
                'valid' => false,
                'message' => 'Pilih minimal 1 kursi.',
            ];
        }

        // Cek duplikat
        if (count($selectedSeats) !== count(array_unique($selectedSeats))) {
            return [
                'valid' => false,
                'message' => 'Terdapat kursi duplikat dalam pilihan.',
            ];
        }

        // Cek jumlah kursi tersedia
        if ($trip->available_seats < count($selectedSeats)) {
            return [
                'valid' => false,
                'message' => "Hanya tersedia {$trip->available_seats} kursi. Anda memilih " . count($selectedSeats) . " kursi.",
            ];
        }

        // Cek kursi sudah dibooking
        $bookedSeats = $trip->booked_seats;
        $conflictingSeats = array_intersect($selectedSeats, $bookedSeats);

        if (!empty($conflictingSeats)) {
            Log::warning('Seat conflict detected', [
                'trip_id' => $trip->id,
                'conflicting_seats' => $conflictingSeats,
            ]);

            return [
                'valid' => false,
                'message' => 'Kursi ' . implode(', ', $conflictingSeats) . ' sudah dibooking oleh pengguna lain.',
            ];
        }

        return ['valid' => true];
    }

    /**
     * Menyimpan kursi yang dipilih ke database (BookingSeat)
     *
     * Fungsi ini membuat record BookingSeat untuk setiap kursi yang dipilih.
     * Record ini menyimpan informasi:
     * - booking_id: ID booking terkait
     * - trip_id: ID trip terkait
     * - seat_number: Nomor kursi (contoh: 'A1', 'B3')
     * - seat_price: Harga per kursi (diambil dari trip->price)
     *
     * Data ini digunakan untuk:
     * - Tracking kursi yang dibooking
     * - Menghindari double booking
     * - Menampilkan detail kursi di e-ticket dan invoice
     *
     * @param \App\Models\Booking $booking Booking yang akan di-assign kursinya
     * @param Trip $trip Trip terkait
     * @param array $selectedSeats Array nomor kursi yang dipilih
     * @return void
     */
    public function assignSeatsToBooking($booking, Trip $trip, array $selectedSeats): void
    {
        foreach ($selectedSeats as $seatNumber) {
            BookingSeat::create([
                'booking_id' => $booking->id,
                'trip_id' => $trip->id,
                'seat_number' => $seatNumber,
                'seat_price' => $trip->price,
            ]);
        }
    }

    /**
     * Mendapatkan status ketersediaan semua kursi untuk trip tertentu
     *
     * Fungsi ini mengembalikan array yang berisi status ketersediaan
     * setiap kursi dalam trip. Status 'true' berarti kursi tersedia,
     * 'false' berarti kursi sudah dibooking.
     *
     * Contoh return:
     * [
     *   'A1' => true,   // tersedia
     *   'A2' => false,  // sudah dibooking
     *   'A3' => true,   // tersedia
     *   ...
     * ]
     *
     * @param Trip $trip Trip yang akan dicek ketersediaan kursinya
     * @return array Array dengan key = nomor kursi, value = bool (true = tersedia, false = terbooking)
     */
    public function getSeatAvailability(Trip $trip): array
    {
        $allSeats = $trip->generateSeatNumbers();
        $bookedSeats = $trip->booked_seats;

        $availability = [];
        foreach ($allSeats as $seatNumber) {
            $availability[$seatNumber] = !in_array($seatNumber, $bookedSeats);
        }

        return $availability;
    }
}

