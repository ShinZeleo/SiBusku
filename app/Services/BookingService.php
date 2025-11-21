<?php

namespace App\Services;

use App\Events\BookingCreated;
use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Trip;
use App\Services\SeatService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    public function __construct(
        private SeatService $seatService
    ) {}

    /**
     * Create booking dengan validasi dan transaction
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // Lock trip untuk mencegah race condition
            $trip = Trip::lockForUpdate()->findOrFail($data['trip_id']);

            // Parse selected seats
            $selectedSeats = array_map('trim', explode(',', $data['selected_seats']));
            $selectedSeats = array_filter($selectedSeats);

            // Validasi kursi menggunakan SeatService
            $validationResult = $this->seatService->validateSeats($trip, $selectedSeats);

            if (!$validationResult['valid']) {
                throw new \Illuminate\Validation\ValidationException(
                    \Illuminate\Support\Facades\Validator::make([], [])
                        ->errors()
                        ->add('selected_seats', $validationResult['message'])
                );
            }

            // Hitung total harga
            $totalPrice = $trip->price * count($selectedSeats);

            // Buat booking
            $booking = Booking::create([
                'user_id' => $data['user_id'],
                'trip_id' => $trip->id,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'seats_count' => count($selectedSeats),
                'selected_seats' => implode(', ', $selectedSeats),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Simpan detail kursi
            $this->seatService->assignSeatsToBooking($booking, $trip, $selectedSeats);

            // Update available_seats di trip
            $trip->decrement('available_seats', count($selectedSeats));

            // Dispatch event
            event(new BookingCreated($booking));

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'trip_id' => $trip->id,
                'seats' => $selectedSeats,
            ]);

            return $booking;
        });
    }

    /**
     * Update booking status dengan logging
     */
    public function updateBookingStatus(Booking $booking, string $newStatus, ?string $keterangan = null): void
    {
        $oldStatus = $booking->status;

        if ($oldStatus === $newStatus) {
            return; // Tidak ada perubahan
        }

        DB::transaction(function () use ($booking, $oldStatus, $newStatus, $keterangan) {
            $booking->update(['status' => $newStatus]);

            // Dispatch event
            event(new BookingStatusUpdated($booking, $oldStatus, $newStatus, $keterangan));

            // Jika dibatalkan, kembalikan kursi
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->releaseSeats($booking);
            }

            Log::info('Booking status updated', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        });
    }

    /**
     * Cancel booking oleh user
     */
    public function cancelBookingByUser(Booking $booking, int $userId): void
    {
        if ($booking->user_id !== $userId) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Anda tidak memiliki akses untuk membatalkan booking ini.');
        }

        if ($booking->status !== 'pending') {
            throw new \Illuminate\Validation\ValidationException(
                \Illuminate\Support\Facades\Validator::make([], [])
                    ->errors()
                    ->add('status', 'Hanya booking dengan status pending yang bisa dibatalkan.')
            );
        }

        $this->updateBookingStatus($booking, 'cancelled', 'Dibatalkan oleh user');
    }

    /**
     * Release seats saat booking dibatalkan
     */
    private function releaseSeats(Booking $booking): void
    {
        $booking->trip()->increment('available_seats', $booking->seats_count);
        $booking->bookingSeats()->delete();

        Log::info('Seats released', [
            'booking_id' => $booking->id,
            'seats_count' => $booking->seats_count,
        ]);
    }
}

