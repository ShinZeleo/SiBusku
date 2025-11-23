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
        $buses = Bus::all();

        if ($routes->isEmpty() || $buses->isEmpty()) {
            return;
        }

        // Tanggal mulai: 23 November 2025
        $startDate = Carbon::create(2025, 11, 23);
        // Tanggal akhir: 31 Januari 2026
        $endDate = Carbon::create(2026, 1, 31);

        // Template trip untuk setiap rute dengan waktu dan harga
        $tripTemplates = [
            // Jakarta - Bandung (populer, banyak trip)
            ['route' => 'Jakarta-Bandung', 'times' => ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'], 'price_range' => [120000, 180000]],
            ['route' => 'Bandung-Jakarta', 'times' => ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'], 'price_range' => [120000, 180000]],

            // Jakarta - Yogyakarta
            ['route' => 'Jakarta-Yogyakarta', 'times' => ['08:00', '12:00', '16:00', '20:00'], 'price_range' => [280000, 350000]],
            ['route' => 'Yogyakarta-Jakarta', 'times' => ['08:00', '12:00', '16:00', '20:00'], 'price_range' => [280000, 350000]],

            // Jakarta - Semarang
            ['route' => 'Jakarta-Semarang', 'times' => ['07:00', '10:00', '13:00', '16:00', '19:00'], 'price_range' => [200000, 280000]],
            ['route' => 'Semarang-Jakarta', 'times' => ['07:00', '10:00', '13:00', '16:00', '19:00'], 'price_range' => [200000, 280000]],

            // Jakarta - Surabaya
            ['route' => 'Jakarta-Surabaya', 'times' => ['08:00', '14:00', '20:00'], 'price_range' => [350000, 450000]],
            ['route' => 'Surabaya-Jakarta', 'times' => ['08:00', '14:00', '20:00'], 'price_range' => [350000, 450000]],

            // Bandung - Surabaya
            ['route' => 'Bandung-Surabaya', 'times' => ['06:00', '10:00', '14:00', '18:00'], 'price_range' => [380000, 480000]],
            ['route' => 'Surabaya-Bandung', 'times' => ['06:00', '10:00', '14:00', '18:00'], 'price_range' => [380000, 480000]],

            // Yogyakarta - Solo
            ['route' => 'Yogyakarta-Solo', 'times' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'], 'price_range' => [50000, 80000]],
            ['route' => 'Solo-Yogyakarta', 'times' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'], 'price_range' => [50000, 80000]],

            // Semarang - Solo
            ['route' => 'Semarang-Solo', 'times' => ['08:00', '12:00', '16:00'], 'price_range' => [70000, 100000]],
            ['route' => 'Solo-Semarang', 'times' => ['08:00', '12:00', '16:00'], 'price_range' => [70000, 100000]],

            // Surabaya - Malang
            ['route' => 'Surabaya-Malang', 'times' => ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00'], 'price_range' => [80000, 120000]],
            ['route' => 'Malang-Surabaya', 'times' => ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00'], 'price_range' => [80000, 120000]],

            // Jakarta - Bogor
            ['route' => 'Jakarta-Bogor', 'times' => ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'], 'price_range' => [30000, 50000]],
            ['route' => 'Bogor-Jakarta', 'times' => ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'], 'price_range' => [30000, 50000]],

            // Denpasar - Ubud
            ['route' => 'Denpasar-Ubud', 'times' => ['08:00', '10:00', '12:00', '14:00', '16:00'], 'price_range' => [50000, 80000]],
            ['route' => 'Ubud-Denpasar', 'times' => ['08:00', '10:00', '12:00', '14:00', '16:00'], 'price_range' => [50000, 80000]],

            // Surabaya - Denpasar
            ['route' => 'Surabaya-Denpasar', 'times' => ['08:00', '14:00', '20:00'], 'price_range' => [250000, 320000]],
            ['route' => 'Denpasar-Surabaya', 'times' => ['08:00', '14:00', '20:00'], 'price_range' => [250000, 320000]],

            // Medan - Pematangsiantar
            ['route' => 'Medan-Pematangsiantar', 'times' => ['07:00', '10:00', '13:00', '16:00'], 'price_range' => [100000, 150000]],
            ['route' => 'Pematangsiantar-Medan', 'times' => ['07:00', '10:00', '13:00', '16:00'], 'price_range' => [100000, 150000]],

            // Jakarta - Palembang
            ['route' => 'Jakarta-Palembang', 'times' => ['08:00', '16:00', '20:00'], 'price_range' => [300000, 400000]],
            ['route' => 'Palembang-Jakarta', 'times' => ['08:00', '16:00', '20:00'], 'price_range' => [300000, 400000]],

            // Makassar - Parepare
            ['route' => 'Makassar-Parepare', 'times' => ['06:00', '10:00', '14:00'], 'price_range' => [150000, 220000]],
            ['route' => 'Parepare-Makassar', 'times' => ['06:00', '10:00', '14:00'], 'price_range' => [150000, 220000]],
        ];

        $tripCount = 0;
        $currentDate = $startDate->copy();

        // Loop setiap hari dari 23 Nov 2025 sampai 31 Jan 2026
        while ($currentDate->lte($endDate)) {
            // Skip hari tertentu jika perlu (opsional)
            // if ($currentDate->isWeekend()) { ... }

            foreach ($tripTemplates as $template) {
                $route = $routes[$template['route']] ?? null;

                if (!$route) {
                    continue;
                }

                // Ambil bus secara random untuk setiap trip
                $availableBuses = $buses->shuffle();

                // Buat trip untuk setiap waktu dalam template
                foreach ($template['times'] as $timeIndex => $time) {
                    // Ambil bus berbeda untuk setiap waktu (atau bisa random)
                    $bus = $availableBuses->get($timeIndex % $availableBuses->count());

                    if (!$bus) {
                        continue;
                    }

                    // Generate harga random dalam range
                    $price = rand($template['price_range'][0], $template['price_range'][1]);

                    // Untuk hari libur (24-25 Des, 1 Jan), kurangi available seats (lebih banyak yang booking)
                    $isHoliday = ($currentDate->month === 12 && ($currentDate->day === 24 || $currentDate->day === 25)) ||
                                 ($currentDate->month === 1 && $currentDate->day === 1);

                    $bookedSeats = $isHoliday ? rand(15, 25) : rand(0, 10);
                    $availableSeats = max($bus->capacity - $bookedSeats, 5);

                    Trip::updateOrCreate(
                        [
                            'route_id' => $route->id,
                            'bus_id' => $bus->id,
                            'departure_date' => $currentDate->format('Y-m-d'),
                            'departure_time' => $time,
                        ],
                        [
                            'price' => $price,
                            'total_seats' => $bus->capacity,
                            'available_seats' => $availableSeats,
                            'status' => 'scheduled',
                        ]
                    );

                    $tripCount++;
                }
            }

            // Pindah ke hari berikutnya
            $currentDate->addDay();
        }

        $this->command->info("Created {$tripCount} trips from {$startDate->format('d M Y')} to {$endDate->format('d M Y')}");
    }
}
