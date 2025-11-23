<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller untuk mengelola bus
 *
 * Controller ini menangani semua operasi CRUD bus untuk admin,
 * termasuk penambahan bus baru, update data, dan penghapusan.
 *
 * @package App\Http\Controllers
 */
class BusController extends Controller
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
     * Menampilkan daftar semua bus (untuk admin)
     *
     * Fungsi ini menampilkan semua bus yang ada di sistem dengan pagination.
     * Data diurutkan berdasarkan nama bus (alphabetical).
     *
     * @return \Illuminate\View\View View admin.buses.index dengan data buses (paginated)
     */
    public function index()
    {
        $buses = Bus::orderBy('name')->paginate(10);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Menampilkan form untuk menambah bus baru
     *
     * @return \Illuminate\View\View View admin.buses.create
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Menyimpan bus baru ke database
     *
     * Validasi:
     * - name: Wajib, string, max 255 karakter
     * - plate_number: Wajib, string, harus unique
     * - capacity: Wajib, integer, min 1
     * - bus_class: Wajib, string, max 255 karakter
     * - is_active: Optional, boolean
     *
     * @param Request $request Request berisi data bus
     * @return \Illuminate\Http\RedirectResponse Redirect ke buses.index dengan success message
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses,plate_number',
            'capacity' => 'required|integer|min:1',
            'bus_class' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Bus::create([
            'name' => $request->name,
            'plate_number' => $request->plate_number,
            'capacity' => $request->capacity,
            'bus_class' => $request->bus_class,
            'is_active' => $request->has('is_active'),
        ]);

        return Redirect::route('buses.index')->with('success', 'Bus berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail bus tertentu
     *
     * @param string $id ID bus yang akan ditampilkan
     * @return \Illuminate\View\View View admin.buses.show dengan data bus
     */
    public function show(string $id)
    {
        $bus = Bus::findOrFail($id);
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Menampilkan form edit bus
     *
     * @param string $id ID bus yang akan diedit
     * @return \Illuminate\View\View View admin.buses.edit dengan data bus
     */
    public function edit(string $id)
    {
        $bus = Bus::findOrFail($id);
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Mengupdate data bus
     *
     * Validasi sama seperti store(), tapi plate_number bisa sama dengan
     * plate_number bus yang sedang diupdate (menggunakan unique rule dengan exception).
     *
     * @param Request $request Request berisi data bus yang akan diupdate
     * @param string $id ID bus yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse Redirect ke buses.index dengan success message
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     */
    public function update(Request $request, string $id)
    {
        $bus = Bus::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:buses,plate_number,' . $id,
            'capacity' => 'required|integer|min:1',
            'bus_class' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $bus->update([
            'name' => $request->name,
            'plate_number' => $request->plate_number,
            'capacity' => $request->capacity,
            'bus_class' => $request->bus_class,
            'is_active' => $request->has('is_active'),
        ]);

        return Redirect::route('buses.index')->with('success', 'Bus berhasil diperbarui.');
    }

    /**
     * Menghapus bus dari database (untuk admin)
     *
     * Validasi:
     * - Bus tidak bisa dihapus jika memiliki trip terkait
     *   (untuk menjaga integritas data)
     *
     * WARNING: Operasi ini tidak bisa di-undo.
     *
     * @param string $id ID bus yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     *         - Redirect ke buses.index dengan success message jika berhasil
     *         - Redirect ke buses.index dengan error message jika bus memiliki trip
     */
    public function destroy(string $id)
    {
        $bus = Bus::findOrFail($id);

        // Jika bus memiliki trip yang aktif, tidak bisa dihapus
        if($bus->trips()->count() > 0){
            return Redirect::route('buses.index')->with('error', 'Bus tidak bisa dihapus karena memiliki trip terkait.');
        }

        $bus->delete();
        return Redirect::route('buses.index')->with('success', 'Bus berhasil dihapus.');
    }
}
