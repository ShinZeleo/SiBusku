<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Seed daftar rute populer agar pilihan kota tidak kosong.
     */
    public function run(): void
    {
        $routes = [
            ['origin_city' => 'Jakarta', 'destination_city' => 'Bandung', 'duration_estimate' => 3.5],
            ['origin_city' => 'Jakarta', 'destination_city' => 'Yogyakarta', 'duration_estimate' => 8.0],
            ['origin_city' => 'Bandung', 'destination_city' => 'Surabaya', 'duration_estimate' => 10.5],
            ['origin_city' => 'Surabaya', 'destination_city' => 'Malang', 'duration_estimate' => 2.5],
            ['origin_city' => 'Semarang', 'destination_city' => 'Solo', 'duration_estimate' => 2.0],
            ['origin_city' => 'Denpasar', 'destination_city' => 'Ubud', 'duration_estimate' => 1.5],
            ['origin_city' => 'Medan', 'destination_city' => 'Pematangsiantar', 'duration_estimate' => 3.0],
            ['origin_city' => 'Makassar', 'destination_city' => 'Parepare', 'duration_estimate' => 5.0],
        ];

        foreach ($routes as $route) {
            Route::updateOrCreate(
                [
                    'origin_city' => $route['origin_city'],
                    'destination_city' => $route['destination_city'],
                ],
                [
                    'duration_estimate' => $route['duration_estimate'],
                    'is_active' => true,
                ]
            );
        }
    }
}
