<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller untuk mengelola trip (jadwal perjalanan bus)
 *
 * Controller ini menangani semua operasi CRUD trip untuk admin,
 * termasuk pembuatan jadwal baru, update, hapus, dan export data.
 *
 * @package App\Http\Controllers
 */
class TripController extends Controller
{
    /**
     * Constructor
     *
     * Catatan: Middleware (admin) diterapkan di route definition,
     * bukan di constructor pada Laravel 11.
     */
    public function __construct()
    {
        // Middleware diterapkan di route, bukan di sini pada Laravel 11
    }

    /**
     * Menampilkan daftar semua trip (untuk admin)
     *
     * Fungsi ini menampilkan semua trip yang ada di sistem dengan pagination.
     * Data diurutkan berdasarkan tanggal keberangkatan (terdekat di atas).
     *
     * @return \Illuminate\View\View View admin.trips.index dengan data trips (paginated)
     */
    public function index()
    {
        $trips = Trip::with(['route', 'bus'])->orderBy('departure_date')->paginate(10);
        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Menampilkan form untuk membuat trip baru
     *
     * Fungsi ini menampilkan form untuk membuat jadwal trip baru.
     * Form memerlukan data: route, bus, tanggal, jam, harga, dan jumlah kursi.
     *
     * Data yang diperlukan:
     * - Daftar bus aktif untuk dropdown
     * - Daftar route aktif untuk dropdown
     *
     * @return \Illuminate\View\View View admin.trips.create dengan data buses dan routes
     */
    public function create()
    {
        $buses = Bus::where('is_active', true)->get();
        $routes = Route::where('is_active', true)->get();
        return view('admin.trips.create', compact('buses', 'routes'));
    }

    /**
     * Menyimpan trip baru ke database
     *
     * Fungsi ini membuat jadwal trip baru dengan validasi:
     * - route_id dan bus_id harus valid
     * - departure_date harus >= hari ini
     * - price harus >= 0
     * - total_seats harus >= 1
     *
     * Catatan: available_seats awalnya sama dengan total_seats
     * (semua kursi tersedia saat trip baru dibuat).
     *
     * @param Request $request Request berisi data trip (route_id, bus_id, departure_date, dll)
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin.trips.index dengan success message
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
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

        return Redirect::route('admin.trips.index')->with('success', 'Trip berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail trip tertentu (bisa diakses publik)
     *
     * Fungsi ini menampilkan detail lengkap dari sebuah trip, termasuk:
     * - Informasi route (kota asal, tujuan, durasi)
     * - Informasi bus (nama, kelas, kapasitas)
     * - Jadwal (tanggal, jam)
     * - Harga dan ketersediaan kursi
     *
     * Catatan: Hanya trip dengan status 'scheduled' yang bisa ditampilkan.
     *
     * @param string $id ID trip yang akan ditampilkan
     * @return \Illuminate\View\View View trips.show dengan data trip
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika trip tidak ditemukan atau tidak scheduled
     */
    public function show(string $id)
    {
        $trip = Trip::with(['route', 'bus'])
            ->where('status', 'scheduled')
            ->findOrFail($id);

        return view('trips.show', compact('trip'));
    }

    /**
     * Menampilkan form edit trip (untuk admin)
     *
     * Fungsi ini menampilkan form untuk mengubah data trip.
     * Form akan diisi dengan data trip yang sudah ada.
     *
     * @param string $id ID trip yang akan diedit
     * @return \Illuminate\View\View View admin.trips.edit dengan data trip, buses, dan routes
     */
    public function edit(string $id)
    {
        $trip = Trip::findOrFail($id);
        $buses = Bus::where('is_active', true)->get();
        $routes = Route::where('is_active', true)->get();
        return view('admin.trips.edit', compact('trip', 'buses', 'routes'));
    }

    /**
     * Mengupdate data trip
     *
     * Fungsi ini mengupdate data trip dengan validasi yang sama seperti store().
     *
     * Logika khusus:
     * - Jika total_seats berubah, available_seats akan disesuaikan
     * - available_seats = total_seats - jumlah kursi yang sudah dipesan
     * - Jika hasil perhitungan < 0, update akan ditolak (tidak bisa mengurangi
     *   total_seats di bawah jumlah kursi yang sudah dipesan)
     *
     * @param Request $request Request berisi data trip yang akan diupdate
     * @param string $id ID trip yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse
     *         - Redirect ke admin.trips.index dengan success message jika berhasil
     *         - Redirect back dengan error jika total_seats tidak valid
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
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

        return Redirect::route('admin.trips.index')->with('success', 'Trip berhasil diperbarui.');
    }

    /**
     * Menghapus trip dari database (untuk admin)
     *
     * Fungsi ini menghapus trip secara permanen dari database.
     *
     * Validasi:
     * - Trip tidak bisa dihapus jika memiliki booking terkait
     *   (untuk menjaga integritas data)
     *
     * WARNING: Operasi ini tidak bisa di-undo. Hanya gunakan jika benar-benar diperlukan.
     *
     * @param string $id ID trip yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     *         - Redirect ke admin.trips.index dengan success message jika berhasil
     *         - Redirect ke admin.trips.index dengan error message jika trip memiliki booking
     */
    public function destroy(string $id)
    {
        $trip = Trip::findOrFail($id);

        // Jika trip memiliki booking, tidak bisa dihapus
        if($trip->bookings()->count() > 0){
            return Redirect::route('admin.trips.index')->with('error', 'Trip tidak bisa dihapus karena memiliki booking terkait.');
        }

        $trip->delete();
        return Redirect::route('admin.trips.index')->with('success', 'Trip berhasil dihapus.');
    }

    /**
     * Mengekspor semua trip ke file CSV
     *
     * Fungsi ini mengekspor semua data trip ke file CSV untuk keperluan
     * laporan atau analisis data. File CSV berisi:
     * - ID Trip
     * - Rute (Asal - Tujuan)
     * - Informasi Bus (Nama, Plat Nomor)
     * - Jadwal (Tanggal, Jam)
     * - Harga per Kursi
     * - Statistik Kursi (Total, Tersedia)
     * - Status Trip
     * - Tanggal Dibuat
     *
     * File akan di-download dengan nama format: trips_YYYY-MM-DD_HH-mm-ss.csv
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     *         Response stream untuk download file CSV
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
