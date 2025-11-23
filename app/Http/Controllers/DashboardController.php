<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk dashboard (halaman utama setelah login)
 *
 * Controller ini menampilkan dashboard yang berbeda berdasarkan role user:
 * - Admin: Dashboard dengan statistik lengkap dan aktivitas terbaru
 * - User: Dashboard dengan booking milik user dan trip yang akan datang
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Constructor
     *
     * Catatan: Middleware (auth) diterapkan di route definition,
     * bukan di constructor pada Laravel 11.
     */
    public function __construct()
    {
        // Middleware diterapkan di route, bukan di sini pada Laravel 11
    }

    /**
     * Menampilkan dashboard berdasarkan role user
     *
     * Fungsi ini menentukan dashboard mana yang akan ditampilkan berdasarkan
     * role user yang sedang login:
     * - Admin: Memanggil adminDashboard()
     * - User: Memanggil userDashboard()
     *
     * @return \Illuminate\View\View
     *         - View admin.dashboard jika user adalah admin
     *         - View user.dashboard jika user adalah user biasa
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Menampilkan dashboard untuk admin
     *
     * Dashboard admin menampilkan:
     * - Statistik total: Bookings, Trips, Buses, Routes
     * - 5 booking terbaru dengan detail user dan trip
     * - 5 trip yang akan datang (departure_date >= hari ini)
     * - 5 log WhatsApp terbaru dengan detail booking
     *
     * Data ini membantu admin untuk:
     * - Memantau aktivitas sistem secara real-time
     * - Melihat booking dan trip yang perlu perhatian
     * - Memantau status notifikasi WhatsApp
     *
     * @return \Illuminate\View\View View admin.dashboard dengan data statistik dan aktivitas terbaru
     */
    private function adminDashboard()
    {
        $totalBookings = Booking::count();
        $totalTrips = Trip::count();
        $totalBuses = Bus::count();
        $totalRoutes = Route::count();

        // Booking terbaru
        $recentBookings = Booking::with(['user', 'trip.route'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Trip yang akan datang
        $upcomingTrips = Trip::with(['route', 'bus'])
            ->whereDate('departure_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('departure_date')
            ->limit(5)
            ->get();

        // WhatsApp logs terbaru
        $recentWhatsAppLogs = \App\Models\WhatsAppLog::with(['booking.user', 'booking.trip.route'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalTrips',
            'totalBuses',
            'totalRoutes',
            'recentBookings',
            'upcomingTrips',
            'recentWhatsAppLogs'
        ));
    }

    /**
     * Menampilkan dashboard untuk user
     *
     * Dashboard user menampilkan:
     * - 5 booking terbaru milik user
     * - Statistik booking: Total, Aktif (pending/confirmed), Selesai
     * - 5 trip yang akan datang untuk user (booking dengan departure_date >= hari ini)
     *
     * Data ini membantu user untuk:
     * - Melihat riwayat booking mereka
     * - Melihat trip yang akan datang
     * - Memantau status booking aktif
     *
     * @return \Illuminate\View\View View user.dashboard dengan data booking dan statistik user
     */
    private function userDashboard()
    {
        $userId = Auth::id();

        $userBookings = Booking::where('user_id', $userId)
            ->with(['trip.route', 'trip.bus'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $totalBookings = Booking::where('user_id', $userId)->count();
        $activeBookingsCount = Booking::where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
        $completedBookingsCount = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Booking trip yang akan datang untuk user ini
        $upcomingTrips = Booking::with(['trip.route', 'trip.bus'])
            ->where('user_id', $userId)
            ->whereHas('trip', function ($query) {
                $query->whereDate('departure_date', '>=', now());
            })
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact(
            'userBookings',
            'totalBookings',
            'upcomingTrips',
            'activeBookingsCount',
            'completedBookingsCount'
        ));
    }
}
