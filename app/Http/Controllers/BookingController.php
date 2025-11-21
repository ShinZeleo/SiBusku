<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\Trip;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'trip.route', 'latestWhatsappLog', 'bookingSeats'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     *
     * Menggunakan BookingService untuk business logic
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
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::with(['trip', 'bookingSeats'])->findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
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
        $oldPaymentStatus = $booking->payment_status;

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
     * Cancel booking oleh user
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        $this->authorize('cancel', $booking);

        try {
            $this->bookingService->cancelBookingByUser($booking, Auth::id());

            return Redirect::route('user.bookings.index')
                ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Throwable $e) {
            return Redirect::back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show success page setelah booking
     */
    public function success(string $id)
    {
        // Load user relation untuk konsistensi data
        $booking = Booking::with(['user', 'trip.route', 'trip.bus', 'bookingSeats', 'latestWhatsappLog'])->findOrFail($id);

        // Hanya pemilik booking yang bisa akses
        if (!Auth::user()->isAdmin() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        $bookingCode = 'SIB-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);

        return view('bookings.success', compact('booking', 'bookingCode'));
    }

    /**
     * Remove the specified resource from storage.
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
     * Download PDF ticket untuk user
     */
    public function downloadTicket(string $id)
    {
        // Load user relation untuk konsistensi data
        $booking = Booking::with([
            'user', // Penting untuk accessor customer_name dan customer_phone
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
     * Export bookings to CSV
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
