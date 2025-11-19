<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $routes = Route::all()->keyBy(fn ($route) => $route->origin_city.'-'.$route->destination_city);
        $buses = Bus::all()->keyBy('name');

        if ($routes->isEmpty() || $buses->isEmpty()) {
            return;
        }

        $tripTemplates = [
            ['route' => 'Jakarta-Bandung', 'bus' => 'Garuda Prima', 'days' => 1, 'time' => '08:00', 'price' => 150000],
            ['route' => 'Jakarta-Bandung', 'bus' => 'Nusantara Jaya', 'days' => 2, 'time' => '17:30', 'price' => 165000],
            ['route' => 'Jakarta-Yogyakarta', 'bus' => 'Langit Biru', 'days' => 3, 'time' => '19:00', 'price' => 320000],
            ['route' => 'Bandung-Surabaya', 'bus' => 'Safari Sentosa', 'days' => 4, 'time' => '06:30', 'price' => 420000],
            ['route' => 'Surabaya-Malang', 'bus' => 'Safari Sentosa', 'days' => 1, 'time' => '09:15', 'price' => 90000],
            ['route' => 'Semarang-Solo', 'bus' => 'Nusantara Jaya', 'days' => 2, 'time' => '14:00', 'price' => 75000],
            ['route' => 'Denpasar-Ubud', 'bus' => 'Bali Dwipa', 'days' => 1, 'time' => '11:00', 'price' => 65000],
            ['route' => 'Medan-Pematangsiantar', 'bus' => 'Garuda Prima', 'days' => 3, 'time' => '07:45', 'price' => 120000],
            ['route' => 'Makassar-Parepare', 'bus' => 'Langit Biru', 'days' => 5, 'time' => '05:30', 'price' => 185000],
        ];

        foreach ($tripTemplates as $template) {
            $route = $routes[$template['route']] ?? null;
            $bus = $buses[$template['bus']] ?? null;

            if (! $route || ! $bus) {
                continue;
            }

            $departureDate = Carbon::now()->addDays($template['days'])->format('Y-m-d');

            Trip::updateOrCreate(
                [
                    'route_id' => $route->id,
                    'bus_id' => $bus->id,
                    'departure_date' => $departureDate,
                    'departure_time' => $template['time'],
                ],
                [
                    'price' => $template['price'],
                    'total_seats' => $bus->capacity,
                    'available_seats' => max($bus->capacity - rand(4, 12), 10),
                    'status' => 'scheduled',
                ]
            );
        }
    }
}
