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

/**
 * Service untuk mengelola business logic booking tiket bus
 *
 * Service ini menangani semua operasi bisnis yang berkaitan dengan booking,
 * termasuk pembuatan booking, update status, dan pembatalan. Service ini
 * menggunakan database transaction untuk memastikan konsistensi data.
 *
 * @package App\Services
 */
class BookingService
{
    /**
     * Constructor - Inject SeatService untuk validasi dan assignment kursi
     *
     * @param SeatService $seatService Service untuk validasi dan pengelolaan kursi
     */
    public function __construct(
        private SeatService $seatService
    ) {}

    /**
     * Membuat booking baru dengan validasi lengkap dan transaction
     *
     * Fungsi ini adalah fungsi utama untuk membuat booking baru. Semua proses
     * dilakukan dalam database transaction untuk memastikan konsistensi data.
     *
     * Proses yang dilakukan:
     * 1. Lock trip untuk mencegah race condition (menggunakan lockForUpdate)
     * 2. Parse dan validasi kursi yang dipilih menggunakan SeatService
     * 3. Hitung total harga (harga per kursi × jumlah kursi)
     * 4. Buat record booking di database
     * 5. Assign kursi ke booking menggunakan SeatService
     * 6. Update available_seats di trip (decrement)
     * 7. Trigger event BookingCreated (akan trigger listener untuk kirim WA)
     * 8. Log aktivitas untuk audit trail
     *
     * Validasi yang dilakukan:
     * - Kursi tidak boleh kosong
     * - Kursi tidak boleh duplikat
     * - Jumlah kursi tidak boleh melebihi available_seats
     * - Kursi tidak boleh sudah dibooking oleh user lain
     *
     * @param array $data Array berisi:
     *                    - user_id: ID user yang membuat booking
     *                    - trip_id: ID trip yang dibooking
     *                    - customer_name: Nama pemesan (optional, default dari user)
     *                    - customer_phone: No HP pemesan (optional, default dari user)
     *                    - selected_seats: String kursi yang dipilih (format: "A1, A2, B3")
     * @return Booking Booking yang baru dibuat
     * @throws \Illuminate\Validation\ValidationException Jika validasi kursi gagal
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika trip atau user tidak ditemukan
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
                $validator = \Illuminate\Support\Facades\Validator::make([], []);
                $validator->errors()->add('selected_seats', $validationResult['message']);
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            // Hitung total harga
            $totalPrice = $trip->price * count($selectedSeats);

            // Get user for consistency
            $user = \App\Models\User::findOrFail($data['user_id']);

            // Buat booking
            // customer_name dan customer_phone disimpan sebagai snapshot untuk kompatibilitas
            // Di view dan service selalu menggunakan accessor yang mengambil dari user
            $booking = Booking::create([
                'user_id' => $data['user_id'],
                'trip_id' => $trip->id,
                'customer_name' => $data['customer_name'] ?? $user->name,
                'customer_phone' => $data['customer_phone'] ?? $user->phone,
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
     * Mengupdate status booking dengan logging dan event handling
     *
     * Fungsi ini mengupdate status booking dan melakukan beberapa operasi:
     * 1. Update status di database
     * 2. Buat log perubahan status (BookingStatusLog)
     * 3. Trigger event BookingStatusUpdated (untuk notifikasi, dll)
     * 4. Jika status menjadi 'cancelled', kembalikan kursi ke trip
     *
     * Catatan:
     * - Jika status tidak berubah, fungsi akan return tanpa melakukan apapun
     * - Semua operasi dilakukan dalam transaction untuk konsistensi
     *
     * @param Booking $booking Booking yang akan diupdate
     * @param string $newStatus Status baru (pending, confirmed, cancelled, completed)
     * @param string|null $keterangan Keterangan perubahan status (optional)
     * @return void
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
     * Membatalkan booking oleh user
     *
     * Fungsi ini memungkinkan user untuk membatalkan booking mereka sendiri.
     * Hanya booking dengan status 'pending' yang bisa dibatalkan oleh user.
     *
     * Validasi:
     * - User harus adalah pemilik booking
     * - Status booking harus 'pending'
     *
     * Proses yang dilakukan:
     * 1. Validasi authorization dan status
     * 2. Panggil updateBookingStatus dengan status 'cancelled'
     * 3. updateBookingStatus akan:
     *    - Update status menjadi 'cancelled'
     *    - Kembalikan kursi ke trip (increment available_seats)
     *    - Hapus booking seats
     *    - Buat log perubahan status
     *    - Trigger event BookingStatusUpdated
     *
     * @param Booking $booking Booking yang akan dibatalkan
     * @param int $userId ID user yang membatalkan (harus sama dengan booking->user_id)
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException Jika user bukan pemilik booking
     * @throws \Illuminate\Validation\ValidationException Jika status bukan 'pending'
     */
    public function cancelBookingByUser(Booking $booking, int $userId): void
    {
        if ($booking->user_id !== $userId) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Anda tidak memiliki akses untuk membatalkan booking ini.');
        }

        if ($booking->status !== 'pending') {
            $validator = \Illuminate\Support\Facades\Validator::make([], []);
            $validator->errors()->add('status', 'Hanya booking dengan status pending yang bisa dibatalkan.');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $this->updateBookingStatus($booking, 'cancelled', 'Dibatalkan oleh user');
    }

    /**
     * Mengembalikan kursi ke trip saat booking dibatalkan
     *
     * Fungsi private ini dipanggil ketika booking dibatalkan untuk:
     * 1. Mengembalikan jumlah kursi ke trip (increment available_seats)
     * 2. Menghapus record booking seats dari database
     *
     * Fungsi ini dipanggil oleh updateBookingStatus ketika status berubah menjadi 'cancelled'.
     *
     * @param Booking $booking Booking yang dibatalkan
     * @return void
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

