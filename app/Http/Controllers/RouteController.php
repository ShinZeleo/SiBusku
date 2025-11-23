<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller untuk mengelola route (rute perjalanan)
 *
 * Controller ini menangani semua operasi CRUD route untuk admin,
 * termasuk penambahan rute baru, update data, dan penghapusan.
 *
 * @package App\Http\Controllers
 */
class RouteController extends Controller
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
     * Menampilkan daftar semua route (untuk admin)
     *
     * Fungsi ini menampilkan semua route yang ada di sistem dengan pagination.
     * Data diurutkan berdasarkan kota asal (alphabetical).
     *
     * @return \Illuminate\View\View View admin.routes.index dengan data routes (paginated)
     */
    public function index()
    {
        $routes = Route::orderBy('origin_city')->paginate(10);
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Menampilkan form untuk menambah route baru
     *
     * @return \Illuminate\View\View View admin.routes.create
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Menyimpan route baru ke database
     *
     * Validasi:
     * - origin_city: Wajib, string, max 255 karakter
     * - destination_city: Wajib, string, max 255 karakter
     * - duration_estimate: Wajib, numeric, min 0.1 (dalam jam)
     * - is_active: Optional, boolean
     *
     * @param Request $request Request berisi data route
     * @return \Illuminate\Http\RedirectResponse Redirect ke routes.index dengan success message
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     */
    public function store(Request $request)
    {
        $request->validate([
            'origin_city' => 'required|string|max:255',
            'destination_city' => 'required|string|max:255',
            'duration_estimate' => 'required|numeric|min:0.1',
            'is_active' => 'boolean',
        ]);

        Route::create([
            'origin_city' => $request->origin_city,
            'destination_city' => $request->destination_city,
            'duration_estimate' => $request->duration_estimate,
            'is_active' => $request->has('is_active'),
        ]);

        return Redirect::route('routes.index')->with('success', 'Rute berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail route tertentu
     *
     * @param string $id ID route yang akan ditampilkan
     * @return \Illuminate\View\View View admin.routes.show dengan data route
     */
    public function show(string $id)
    {
        $route = Route::findOrFail($id);
        return view('admin.routes.show', compact('route'));
    }

    /**
     * Menampilkan form edit route
     *
     * @param string $id ID route yang akan diedit
     * @return \Illuminate\View\View View admin.routes.edit dengan data route
     */
    public function edit(string $id)
    {
        $route = Route::findOrFail($id);
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Mengupdate data route
     *
     * Validasi sama seperti store().
     *
     * @param Request $request Request berisi data route yang akan diupdate
     * @param string $id ID route yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse Redirect ke routes.index dengan success message
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     */
    public function update(Request $request, string $id)
    {
        $route = Route::findOrFail($id);

        $request->validate([
            'origin_city' => 'required|string|max:255',
            'destination_city' => 'required|string|max:255',
            'duration_estimate' => 'required|numeric|min:0.1',
            'is_active' => 'boolean',
        ]);

        $route->update([
            'origin_city' => $request->origin_city,
            'destination_city' => $request->destination_city,
            'duration_estimate' => $request->duration_estimate,
            'is_active' => $request->has('is_active'),
        ]);

        return Redirect::route('routes.index')->with('success', 'Rute berhasil diperbarui.');
    }

    /**
     * Menghapus route dari database (untuk admin)
     *
     * Validasi:
     * - Route tidak bisa dihapus jika memiliki trip terkait
     *   (untuk menjaga integritas data)
     *
     * WARNING: Operasi ini tidak bisa di-undo.
     *
     * @param string $id ID route yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     *         - Redirect ke routes.index dengan success message jika berhasil
     *         - Redirect ke routes.index dengan error message jika route memiliki trip
     */
    public function destroy(string $id)
    {
        $route = Route::findOrFail($id);

        // Jika rute memiliki trip yang aktif, tidak bisa dihapus
        if($route->trips()->count() > 0){
            return Redirect::route('routes.index')->with('error', 'Rute tidak bisa dihapus karena memiliki trip terkait.');
        }

        $route->delete();
        return Redirect::route('routes.index')->with('success', 'Rute berhasil dihapus.');
    }
}
