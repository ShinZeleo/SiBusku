<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    public function __construct()
    {
        // Middleware diterapkan di route, bukan di sini pada Laravel 11
    }

    /**
     * Display a listing of the user's bookings.
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
     * Display the specified booking.
     */
    public function show($id)
    {
        // Load user relation untuk konsistensi data (accessor akan menggunakan user->name dan user->phone)
        $booking = Booking::with(['user', 'trip.route', 'trip.bus', 'latestWhatsappLog'])
            ->findOrFail($id);

        // Hanya pemilik booking yang bisa melihat
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.bookings.show', compact('booking'));
    }
}
