<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Route;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda
     */
    public function index()
    {
        $activeRoutes = Route::where('is_active', true)->get();
        $originCities = $activeRoutes->pluck('origin_city')->unique()->sort()->values();
        $destinationCities = $activeRoutes->pluck('destination_city')->unique()->sort()->values();

        $trips = Trip::with(['route', 'bus'])
            ->where('status', 'scheduled')
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->limit(5)
            ->get();

        return view('home', [
            'originCities' => $originCities,
            'destinationCities' => $destinationCities,
            'trips' => $trips,
        ]);
    }

    /**
     * Menampilkan halaman pencarian trip
     */
    public function search(Request $request)
    {
        $data = $request->validate([
            'origin_city' => 'required|string',
            'destination_city' => 'required|string|different:origin_city',
            'departure_date' => 'required|date|after_or_equal:today',
        ]);

        $matchingRouteIds = Route::where('origin_city', $data['origin_city'])
            ->where('destination_city', $data['destination_city'])
            ->pluck('id');

        $trips = Trip::with(['route', 'bus'])
            ->whereIn('route_id', $matchingRouteIds)
            ->whereDate('departure_date', $data['departure_date'])
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_time')
            ->get();

        $originCity = $data['origin_city'];
        $destinationCity = $data['destination_city'];
        $departureDate = $data['departure_date'];

        return view('trips.search-results', compact('trips', 'originCity', 'destinationCity', 'departureDate'));
    }

    /**
     * Menampilkan form pencarian trip
     */
    public function showSearchForm()
    {
        $activeRoutes = Route::where('is_active', true)->get();

        return view('trips.search', [
            'originCities' => $activeRoutes->pluck('origin_city')->unique()->sort()->values(),
            'destinationCities' => $activeRoutes->pluck('destination_city')->unique()->sort()->values(),
        ]);
    }
}
