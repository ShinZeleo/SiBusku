<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BusController extends Controller
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
        $buses = Bus::orderBy('name')->paginate(10);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bus = Bus::findOrFail($id);
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bus = Bus::findOrFail($id);
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
