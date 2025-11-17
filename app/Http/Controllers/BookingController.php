<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BookingController extends Controller
{
    public function __construct()
    {
        // Middleware diterapkan di route, bukan di sini pada Laravel 11
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'trip.route'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404); // Booking tidak dibuat langsung dari sini
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'seats_count' => 'required|integer|min:1',
        ]);

        $trip = Trip::findOrFail($request->trip_id);

        // Cek ketersediaan kursi
        if ($trip->available_seats < $request->seats_count) {
            return Redirect::back()->withErrors(['seats_count' => 'Kursi tidak tersedia. Hanya ' . $trip->available_seats . ' kursi tersisa.']);
        }

        // Hitung total harga
        $totalPrice = $trip->price * $request->seats_count;

        // Kurangi kursi yang tersedia
        $trip->decrement('available_seats', $request->seats_count);

        // Buat booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'trip_id' => $request->trip_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'seats_count' => $request->seats_count,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Kirim notifikasi WhatsApp
        \App\Services\WhatsAppService::notifyBookingCreated($booking);

        return Redirect::route('user.bookings.index')->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran segera.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'trip.route', 'trip.bus'])->findOrFail($id);

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
        $booking = Booking::findOrFail($id);
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
        ]);

        $oldStatus = $booking->status;
        $booking->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        // Jika status booking diubah menjadi confirmed, kirim notifikasi WA
        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            \App\Services\WhatsAppService::notifyBookingConfirmed($booking);
        }

        return Redirect::route('admin.bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);

        // Kembalikan jumlah kursi ke trip jika booking dibatalkan
        if ($booking->status !== 'cancelled') {
            $booking->trip()->increment('available_seats', $booking->seats_count);
        }

        $booking->delete();
        return Redirect::route('admin.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }

    /**
     * Export bookings to CSV
     */
    public function exportCsv()
    {
        $bookings = Booking::with(['user', 'trip.route', 'trip.bus'])->get();

        $fileName = 'bookings_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
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
                'Total Harga',
                'Status Booking',
                'Status Pembayaran',
                'Tanggal Booking'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->customer_name,
                    $booking->customer_phone,
                    $booking->trip->route->origin_city . ' - ' . $booking->trip->route->destination_city,
                    $booking->trip->departure_date,
                    $booking->trip->departure_time,
                    $booking->seats_count,
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
