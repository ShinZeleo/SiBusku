<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BusSeatController extends Controller
{
    /**
     * Show form untuk mengelola seat layout bus
     */
    public function edit(Bus $bus)
    {
        $seats = $bus->seats()->ordered()->get();
        return view('admin.buses.seats', compact('bus', 'seats'));
    }

    /**
     * Update seat layout bus
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

