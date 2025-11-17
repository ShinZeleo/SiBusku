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
        $routes = Route::where('is_active', true)->get();
        $trips = Trip::with(['route', 'bus'])
            ->where('status', 'scheduled')
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->limit(5)
            ->get();

        return view('home', compact('routes', 'trips'));
    }

    /**
     * Menampilkan halaman pencarian trip
     */
    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|exists:routes,id',
            'destination' => 'required|exists:routes,id',
            'departure_date' => 'required|date|after_or_equal:today',
        ]);

        $trips = Trip::with(['route', 'bus'])
            ->where('route_id', $request->origin)
            ->whereDate('departure_date', $request->departure_date)
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_time')
            ->get();

        // Filter berdasarkan tujuan jika diperlukan
        if ($request->destination) {
            $trips = $trips->filter(function ($trip) use ($request) {
                return $trip->route->destination_city == Route::findOrFail($request->destination)->origin_city ||
                       $trip->route->destination_city == Route::findOrFail($request->destination)->destination_city;
            })->values();
        }

        $originCity = Route::findOrFail($request->origin)->origin_city;
        $destinationCity = Route::findOrFail($request->destination)->destination_city;
        $departureDate = $request->departure_date;

        return view('trips.search-results', compact('trips', 'originCity', 'destinationCity', 'departureDate'));
    }

    /**
     * Menampilkan form pencarian trip
     */
    public function showSearchForm()
    {
        $routes = Route::where('is_active', true)->get();
        return view('trips.search', compact('routes'));
    }
}
