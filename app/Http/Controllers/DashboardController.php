<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware diterapkan di route, bukan di sini pada Laravel 11
    }

    /**
     * Menampilkan dashboard berdasarkan role user
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
