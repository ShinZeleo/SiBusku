<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola booking milik user
 *
 * Controller ini menangani operasi yang berkaitan dengan booking
 * yang dimiliki oleh user yang sedang login. User hanya bisa melihat
 * dan mengelola booking mereka sendiri.
 *
 * @package App\Http\Controllers
 */
class UserBookingController extends Controller
{
    /**
     * Menampilkan daftar semua booking milik user yang sedang login
     *
     * Fungsi ini menampilkan semua booking yang dimiliki oleh user
     * yang sedang login, dengan pagination (10 booking per halaman).
     *
     * Data yang ditampilkan:
     * - Detail trip (route, bus)
     * - Status booking
     * - Log WhatsApp terbaru
     *
     * Data diurutkan berdasarkan tanggal dibuat (terbaru di atas).
     *
     * @return \Illuminate\View\View View user.bookings.index dengan data bookings (paginated)
     */
    public function index()
    {
        $bookings = Booking::with(['trip.route', 'trip.bus', 'latestWhatsappLog'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan detail lengkap booking tertentu milik user
     *
     * Fungsi ini menampilkan detail lengkap dari sebuah booking, termasuk:
     * - Informasi trip lengkap (rute, tanggal, jam, bus, durasi)
     * - Informasi booking (nama pemesan, no HP, kursi, harga)
     * - Status booking dan pembayaran
     * - Timeline status booking (Dipesan → Dikonfirmasi → Selesai)
     * - Log WhatsApp terbaru
     * - History perubahan status
     *
     * Authorization:
     * - User hanya bisa melihat booking miliknya sendiri
     * - Jika user mencoba melihat booking milik user lain, akan mendapat error 403
     *
     * Progress Width Calculation:
     * - 'confirmed' atau 'completed': 100% (progress penuh)
     * - 'pending': 33% (baru dipesan)
     * - 'cancelled': 0% (dibatalkan)
     *
     * @param int|string $id ID booking yang akan ditampilkan
     * @return \Illuminate\View\View View user.bookings.show dengan data booking dan progressWidth
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika booking tidak ditemukan
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 jika user tidak berhak
     */
    public function show($id)
    {
        $booking = Booking::with(['user', 'trip.route', 'trip.bus', 'latestWhatsappLog', 'statusLogs'])
            ->findOrFail($id);

        // Hanya pemilik booking yang bisa melihat
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Calculate progress width for status timeline
        $progressWidth = '0%';
        if ($booking->status === 'confirmed' || $booking->status === 'completed') {
            $progressWidth = '100%';
        } elseif ($booking->status === 'pending') {
            $progressWidth = '33%';
        } elseif ($booking->status === 'cancelled') {
            $progressWidth = '0%';
        }

        return view('user.bookings.show', compact('booking', 'progressWidth'));
    }
}
