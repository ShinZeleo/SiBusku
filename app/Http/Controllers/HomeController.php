<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Route;
use Illuminate\Http\Request;

/**
 * Controller untuk halaman publik (home dan pencarian trip)
 *
 * Controller ini menangani halaman beranda dan fungsi pencarian trip
 * yang dapat diakses oleh semua user (guest dan authenticated).
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan form pencarian
     *
     * Fungsi ini menampilkan:
     * - Form pencarian trip (kota asal, tujuan, tanggal)
     * - Daftar kota asal dan tujuan yang tersedia (dari routes aktif)
     * - 5 trip terdekat yang akan datang sebagai rekomendasi
     *
     * Data yang diambil:
     * - Semua route aktif untuk dropdown kota
     * - 5 trip terdekat dengan status 'scheduled' dan tanggal >= hari ini
     *
     * @return \Illuminate\View\View View home dengan data originCities, destinationCities, dan trips
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
     * Mencari trip berdasarkan kriteria pencarian
     *
     * Fungsi ini melakukan pencarian trip berdasarkan:
     * - Kota asal (origin_city)
     * - Kota tujuan (destination_city)
     * - Tanggal keberangkatan (departure_date)
     *
     * Validasi:
     * - origin_city dan destination_city harus berbeda
     * - departure_date harus >= hari ini (tidak bisa booking untuk tanggal lalu)
     *
     * Hasil pencarian:
     * - Hanya menampilkan trip dengan status 'scheduled'
     * - Hanya menampilkan trip yang masih ada kursi tersedia (available_seats > 0)
     * - Diurutkan berdasarkan jam keberangkatan (departure_time)
     *
     * @param Request $request Request berisi origin_city, destination_city, dan departure_date
     * @return \Illuminate\View\View View trips.search-results dengan data trips dan kriteria pencarian
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
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
     * Menampilkan halaman form pencarian trip (alternatif route)
     *
     * Fungsi ini menampilkan halaman khusus untuk pencarian trip.
     * Mirip dengan index(), tapi ini route terpisah jika diperlukan
     * untuk halaman pencarian yang lebih fokus.
     *
     * @return \Illuminate\View\View View trips.search dengan data originCities dan destinationCities
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
