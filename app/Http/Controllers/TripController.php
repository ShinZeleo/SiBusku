<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TripController extends Controller
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
        $trips = Trip::with(['route', 'bus'])->orderBy('departure_date')->paginate(10);
        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buses = Bus::where('is_active', true)->get();
        $routes = Route::where('is_active', true)->get();
        return view('admin.trips.create', compact('buses', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
        ]);

        Trip::create([
            'route_id' => $request->route_id,
            'bus_id' => $request->bus_id,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'price' => $request->price,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats, // Awalnya tersedia semua
        ]);

        return Redirect::route('trips.index')->with('success', 'Trip berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trip = Trip::with(['route', 'bus'])->findOrFail($id);
        return view('admin.trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trip = Trip::findOrFail($id);
        $buses = Bus::where('is_active', true)->get();
        $routes = Route::where('is_active', true)->get();
        return view('admin.trips.edit', compact('trip', 'buses', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $trip = Trip::findOrFail($id);

        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
        ]);

        // Jika total_seats berubah, kita harus menyesuaikan available_seats
        $totalBookedSeats = $trip->bookings()->sum('seats_count');
        $newAvailableSeats = $request->total_seats - $totalBookedSeats;

        if ($newAvailableSeats < 0) {
            return Redirect::back()->withErrors(['total_seats' => 'Jumlah kursi tidak bisa kurang dari jumlah kursi yang sudah dipesan.']);
        }

        $trip->update([
            'route_id' => $request->route_id,
            'bus_id' => $request->bus_id,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'price' => $request->price,
            'total_seats' => $request->total_seats,
            'available_seats' => $newAvailableSeats,
        ]);

        return Redirect::route('trips.index')->with('success', 'Trip berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trip = Trip::findOrFail($id);

        // Jika trip memiliki booking, tidak bisa dihapus
        if($trip->bookings()->count() > 0){
            return Redirect::route('trips.index')->with('error', 'Trip tidak bisa dihapus karena memiliki booking terkait.');
        }

        $trip->delete();
        return Redirect::route('trips.index')->with('success', 'Trip berhasil dihapus.');
    }

    /**
     * Export trips to CSV
     */
    public function exportCsv()
    {
        $trips = Trip::with(['route', 'bus'])->get();

        $fileName = 'trips_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($trips) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID Trip',
                'Rute',
                'Kota Asal',
                'Kota Tujuan',
                'Nama Bus',
                'Plat Nomor',
                'Tanggal Berangkat',
                'Jam Berangkat',
                'Harga per Kursi',
                'Total Kursi',
                'Kursi Tersedia',
                'Status',
                'Tanggal Dibuat'
            ]);

            foreach ($trips as $trip) {
                fputcsv($file, [
                    $trip->id,
                    $trip->route->origin_city . ' - ' . $trip->route->destination_city,
                    $trip->route->origin_city,
                    $trip->route->destination_city,
                    $trip->bus->name,
                    $trip->bus->plate_number,
                    $trip->departure_date,
                    $trip->departure_time,
                    $trip->price,
                    $trip->total_seats,
                    $trip->available_seats,
                    $trip->status,
                    $trip->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
