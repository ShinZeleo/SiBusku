<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RouteController extends Controller
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
        $routes = Route::orderBy('origin_city')->paginate(10);
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $route = Route::findOrFail($id);
        return view('admin.routes.show', compact('route'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $route = Route::findOrFail($id);
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
