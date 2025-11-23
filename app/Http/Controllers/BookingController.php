<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Trip;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller untuk mengelola booking tiket bus
 *
 * Controller ini menangani semua operasi CRUD booking untuk admin,
 * termasuk pembuatan booking baru, update status, pembatalan, dan export data.
 *
 * @package App\Http\Controllers
 */
class BookingController extends Controller
{
    /**
     * Constructor - Inject BookingService untuk business logic
     *
     * BookingService digunakan untuk memisahkan business logic dari controller,
     * sehingga controller hanya fokus pada HTTP request/response handling.
     *
     * @param BookingService $bookingService Service untuk logika bisnis booking
     */
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Menampilkan daftar semua booking (untuk admin)
     *
     * Fungsi ini menampilkan semua booking yang ada di sistem dengan pagination.
     * Data yang ditampilkan termasuk informasi user, trip, route, dan status WhatsApp.
     *
     * @return \Illuminate\View\View View admin.bookings.index dengan data bookings
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'trip.route', 'latestWhatsappLog', 'bookingSeats'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan form untuk membuat booking baru
     *
     * Fungsi ini menampilkan halaman form booking setelah user memilih trip.
     * Form ini akan menampilkan detail trip, bus, dan form untuk memilih kursi.
     *
     * @param Request $request HTTP request yang berisi trip_id dan optional seats
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         - View bookings.create jika trip_id valid
     *         - Redirect ke home jika trip_id tidak ada
     */
    public function create(Request $request)
    {
        $tripId = $request->query('trip_id');
        if (!$tripId) {
            return Redirect::route('home')->with('error', 'Pilih trip terlebih dahulu.');
        }

        $trip = Trip::with(['route', 'bus'])->findOrFail($tripId);
        $selectedSeats = $request->query('seats', '');

        return view('bookings.create', compact('trip', 'selectedSeats'));
    }

    /**
     * Menyimpan booking baru ke database
     *
     * Fungsi ini menerima data booking dari form, melakukan validasi,
     * dan menyimpan booking melalui BookingService. Setelah berhasil,
     * user akan diarahkan ke halaman success dengan kode booking.
     *
     * Proses yang dilakukan:
     * 1. Validasi input menggunakan StoreBookingRequest
     * 2. Memanggil BookingService untuk membuat booking (dengan transaction)
     * 3. BookingService akan:
     *    - Validasi kursi yang dipilih
     *    - Hitung total harga
     *    - Simpan booking dan booking seats
     *    - Update available_seats di trip
     *    - Trigger event BookingCreated (untuk kirim WA)
     * 4. Redirect ke success page dengan booking code
     *
     * @param StoreBookingRequest $request Request yang sudah divalidasi
     * @return \Illuminate\Http\RedirectResponse
     *         - Redirect ke success page jika berhasil
     *         - Redirect back dengan error jika gagal
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking([
                'user_id' => Auth::id(),
                'trip_id' => $request->trip_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'selected_seats' => $request->selected_seats,
            ]);

            // Redirect ke success page dengan booking code
            return Redirect::route('bookings.success', $booking->id)
                ->with('booking_code', 'SIB-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Redirect::back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat membuat booking. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan detail booking tertentu
     *
     * Fungsi ini menampilkan detail lengkap dari sebuah booking, termasuk:
     * - Informasi user yang melakukan booking
     * - Detail trip (rute, tanggal, jam, bus)
     * - Kursi yang dipilih
     * - Status booking dan pembayaran
     * - Log WhatsApp terbaru
     * - History perubahan status
     *
     * Authorization:
     * - Admin bisa melihat semua booking
     * - User hanya bisa melihat booking miliknya sendiri
     *
     * @param string $id ID booking yang akan ditampilkan
     * @return \Illuminate\View\View View bookings.show dengan data booking
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika booking tidak ditemukan
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 jika user tidak berhak
     */
    public function show(string $id)
    {
        $booking = Booking::with([
            'user',
            'trip.route',
            'trip.bus',
            'latestWhatsappLog',
            'bookingSeats',
            'statusLogs.user'
        ])->findOrFail($id);

        // Hanya admin atau pemilik booking yang bisa melihat
        if (!Auth::user()->isAdmin() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Menampilkan form edit booking (untuk admin)
     *
     * Fungsi ini menampilkan form untuk mengubah status booking dan payment status.
     * Hanya admin yang bisa mengakses fungsi ini.
     *
     * @param string $id ID booking yang akan diedit
     * @return \Illuminate\View\View View admin.bookings.edit dengan data booking
     */
    public function edit(string $id)
    {
        $booking = Booking::with(['trip', 'bookingSeats'])->findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Mengupdate status booking dan payment status
     *
     * Fungsi ini digunakan oleh admin untuk mengubah status booking.
     * Ketika status diubah menjadi 'confirmed', sistem akan otomatis
     * mengirim notifikasi WhatsApp ke user.
     *
     * Proses yang dilakukan:
     * 1. Validasi input (status dan payment_status)
     * 2. Update booking status menggunakan BookingService
     * 3. BookingService akan:
     *    - Update status di database
     *    - Buat log perubahan status (BookingStatusLog)
     *    - Trigger event BookingStatusUpdated
     *    - Jika status menjadi 'cancelled', kembalikan kursi ke trip
     * 4. Jika status menjadi 'confirmed', kirim notifikasi WA
     *
     * @param Request $request Request berisi status, payment_status, dan optional keterangan
     * @param string $id ID booking yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin.bookings.index dengan success message
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $oldStatus = $booking->status;

        // Update booking
        $booking->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        // Update status menggunakan service (akan trigger event)
        if ($oldStatus !== $request->status) {
            $this->bookingService->updateBookingStatus($booking, $request->status, $request->keterangan);
        }

        // Jika status booking diubah menjadi confirmed, kirim notifikasi WA
        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            try {
                \App\Services\WhatsAppService::notifyBookingConfirmed($booking);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('WhatsApp confirmation notification failed', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return Redirect::route('admin.bookings.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Membatalkan booking oleh user
     *
     * Fungsi ini memungkinkan user untuk membatalkan booking mereka sendiri
     * yang masih berstatus 'pending'. Setelah dibatalkan, kursi akan dikembalikan
     * ke trip dan status booking berubah menjadi 'cancelled'.
     *
     * Authorization:
     * - User hanya bisa membatalkan booking miliknya sendiri
     * - Hanya booking dengan status 'pending' yang bisa dibatalkan
     *
     * Proses yang dilakukan:
     * 1. Cek apakah user adalah pemilik booking
     * 2. Cek apakah status booking adalah 'pending'
     * 3. Panggil BookingService->cancelBookingByUser()
     * 4. BookingService akan:
     *    - Update status menjadi 'cancelled'
     *    - Kembalikan kursi ke trip (increment available_seats)
     *    - Hapus booking seats
     *    - Buat log perubahan status
     *    - Trigger event BookingStatusUpdated
     *
     * @param Request $request HTTP request (tidak digunakan, hanya untuk consistency)
     * @param string $id ID booking yang akan dibatalkan
     * @return \Illuminate\Http\RedirectResponse
     *         - Redirect ke user.bookings.index dengan success message jika berhasil
     *         - Redirect back dengan error jika gagal
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 jika user tidak berhak
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        // Manual authorization check (matching BookingPolicy logic)
        // User hanya bisa cancel booking mereka sendiri yang masih pending
        if ($booking->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk membatalkan booking ini.');
        }

        if ($booking->status !== 'pending') {
            return Redirect::back()
                ->withErrors(['error' => 'Hanya booking dengan status pending yang bisa dibatalkan.']);
        }

        try {
            $this->bookingService->cancelBookingByUser($booking, $user->id);

            return Redirect::route('user.bookings.index')
                ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::back()
                ->withErrors($e->errors());
        } catch (\Throwable $e) {
            return Redirect::back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman sukses setelah booking dibuat
     *
     * Fungsi ini menampilkan halaman konfirmasi setelah user berhasil membuat booking.
     * Halaman ini menampilkan:
     * - Kode booking (format: SIB-0001)
     * - Detail booking lengkap
     * - Informasi trip dan kursi
     * - Link untuk download e-ticket
     * - Link untuk melihat riwayat booking
     *
     * Authorization:
     * - Admin bisa melihat semua booking
     * - User hanya bisa melihat booking miliknya sendiri
     *
     * @param string $id ID booking yang baru dibuat
     * @return \Illuminate\View\View View bookings.success dengan data booking dan booking code
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 jika user tidak berhak
     */
    public function success(string $id)
    {
        $booking = Booking::with(['user', 'trip.route', 'trip.bus', 'bookingSeats', 'latestWhatsappLog'])->findOrFail($id);

        // Hanya pemilik booking yang bisa akses
        if (!Auth::user()->isAdmin() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        $bookingCode = 'SIB-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);

        return view('bookings.success', compact('booking', 'bookingCode'));
    }

    /**
     * Menghapus booking dari database (untuk admin)
     *
     * Fungsi ini menghapus booking secara permanen dari database.
     * Sebelum menghapus, sistem akan:
     * - Kembalikan kursi ke trip jika booking belum dibatalkan
     * - Hapus semua booking seats terkait
     *
     * WARNING: Operasi ini tidak bisa di-undo. Hanya gunakan jika benar-benar diperlukan.
     *
     * @param string $id ID booking yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin.bookings.index dengan success message
     */
    public function destroy(string $id)
    {
        $booking = Booking::with('bookingSeats')->findOrFail($id);

        DB::transaction(function () use ($booking) {
            // Kembalikan jumlah kursi ke trip jika booking belum dibatalkan
            if ($booking->status !== 'cancelled') {
                $booking->trip()->increment('available_seats', $booking->seats_count);
            }

            // Hapus booking seats
            $booking->bookingSeats()->delete();

            // Hapus booking
            $booking->delete();
        });

        return Redirect::route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    /**
     * Mengunduh e-ticket dalam format PDF
     *
     * Fungsi ini menghasilkan dan mengunduh e-ticket booking dalam format PDF.
     * E-ticket berisi:
     * - Kode booking
     * - Detail trip (rute, tanggal, jam, bus)
     * - Kursi yang dipilih
     * - Informasi pemesan
     * - QR Code untuk verifikasi
     *
     * Authorization:
     * - Admin bisa download semua e-ticket
     * - User hanya bisa download e-ticket miliknya sendiri
     *
     * @param string $id ID booking yang akan di-download e-ticket-nya
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     *         - PDF file download jika berhasil
     *         - Redirect back dengan error jika gagal (misalnya package dompdf tidak terinstall)
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 jika user tidak berhak
     */
    public function downloadTicket(string $id)
    {
        $booking = Booking::with([
            'user',
            'trip.route',
            'trip.bus',
            'bookingSeats'
        ])->findOrFail($id);

        // Hanya pemilik booking yang bisa download
        if (!Auth::user()->isAdmin() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Generate QR Code sederhana (bisa diganti dengan library QR code)
        $qrData = "SIBUSKU|{$booking->id}|{$booking->customer_phone}";

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bookings.ticket-pdf', compact('booking', 'qrData'));
            return $pdf->download("ticket-{$booking->id}.pdf");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('PDF generation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return Redirect::back()
                ->with('error', 'Gagal menghasilkan PDF. Pastikan package barryvdh/laravel-dompdf sudah terinstall.');
        }
    }

    /**
     * Mengekspor semua booking ke file CSV
     *
     * Fungsi ini mengekspor semua data booking ke file CSV untuk keperluan
     * laporan atau analisis data. File CSV berisi:
     * - ID Booking
     * - Nama Pemesan
     * - No. HP
     * - Rute (Asal - Tujuan)
     * - Tanggal & Jam Berangkat
     * - Jumlah & Nomor Kursi
     * - Total Harga
     * - Status Booking & Pembayaran
     * - Tanggal Booking
     *
     * File akan di-download dengan nama format: bookings_YYYY-MM-DD_HH-mm-ss.csv
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     *         Response stream untuk download file CSV
     */
    public function exportCsv()
    {
        $bookings = Booking::with(['user', 'trip.route', 'trip.bus', 'bookingSeats'])->get();

        $fileName = 'bookings_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, pre-check=0, post-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID Booking',
                'Nama Pemesan',
                'No. HP',
                'Rute',
                'Tanggal Berangkat',
                'Jam Berangkat',
                'Jumlah Kursi',
                'Nomor Kursi',
                'Total Harga',
                'Status Booking',
                'Status Pembayaran',
                'Tanggal Booking'
            ]);

            foreach ($bookings as $booking) {
                $seatNumbers = $booking->bookingSeats->pluck('seat_number')->join(', ');

                fputcsv($file, [
                    $booking->id,
                    $booking->customer_name,
                    $booking->customer_phone,
                    $booking->trip->route->origin_city . ' - ' . $booking->trip->route->destination_city,
                    $booking->trip->departure_date,
                    $booking->trip->departure_time,
                    $booking->seats_count,
                    $seatNumbers ?: '-',
                    $booking->total_price,
                    $booking->status,
                    $booking->payment_status,
                    $booking->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
