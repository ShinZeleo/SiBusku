<?php

namespace App\Services;

use App\Models\BookingSeat;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;

class SeatService
{
    /**
     * Validasi kursi yang dipilih
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
     * Assign seats ke booking
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
     * Get seat availability status untuk trip
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

