<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller untuk mengelola layout kursi bus (untuk admin)
 *
 * Controller ini menangani pengaturan layout kursi untuk setiap bus,
 * termasuk penentuan posisi kursi (row, col, section).
 *
 * @package App\Http\Controllers
 */
class BusSeatController extends Controller
{
    /**
     * Menampilkan form untuk mengelola layout kursi bus
     *
     * Fungsi ini menampilkan form untuk mengatur layout kursi bus.
     * Layout kursi menentukan:
     * - Nomor kursi (contoh: A1, A2, B3)
     * - Posisi baris (row_index)
     * - Posisi kolom (col_index)
     * - Section (front, middle, back)
     *
     * Layout ini digunakan oleh seat picker untuk menampilkan kursi
     * dalam format grid yang sesuai dengan layout fisik bus.
     *
     * @param Bus $bus Bus yang akan diatur layout kursinya (route model binding)
     * @return \Illuminate\View\View View admin.buses.seats dengan data bus dan seats
     */
    public function edit(Bus $bus)
    {
        $seats = $bus->seats()->ordered()->get();
        return view('admin.buses.seats', compact('bus', 'seats'));
    }

    /**
     * Mengupdate layout kursi bus
     *
     * Fungsi ini mengupdate layout kursi untuk bus tertentu.
     *
     * Proses yang dilakukan:
     * 1. Validasi input (array seats dengan seat_number, row_index, col_index)
     * 2. Hapus semua seat lama untuk bus ini
     * 3. Buat seat baru berdasarkan data yang dikirim
     *
     * Validasi:
     * - seats: Wajib, array
     * - seats.*.seat_number: Wajib, string, max 10 karakter
     * - seats.*.row_index: Wajib, integer, min 0
     * - seats.*.col_index: Wajib, integer, min 0
     *
     * Catatan: Semua seat lama akan dihapus dan diganti dengan yang baru.
     * Pastikan semua kursi dikirim dalam request untuk menghindari kehilangan data.
     *
     * @param Request $request Request berisi array seats dengan layout baru
     * @param Bus $bus Bus yang akan diupdate layout kursinya (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect ke admin.buses.index dengan success message
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     */
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'seats' => 'required|array',
            'seats.*.seat_number' => 'required|string|max:10',
            'seats.*.row_index' => 'required|integer|min:0',
            'seats.*.col_index' => 'required|integer|min:0',
        ]);

        // Hapus semua seat lama
        $bus->seats()->delete();

        // Buat seat baru
        foreach ($request->seats as $seatData) {
            BusSeat::create([
                'bus_id' => $bus->id,
                'seat_number' => $seatData['seat_number'],
                'row_index' => $seatData['row_index'],
                'col_index' => $seatData['col_index'],
                'deck' => $seatData['deck'] ?? null,
                'section' => $seatData['section'] ?? null,
                'is_active' => true,
            ]);
        }

        return Redirect::route('admin.buses.index')
            ->with('success', 'Layout kursi bus berhasil diperbarui.');
    }
}

