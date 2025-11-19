<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            ['name' => 'Garuda Prima', 'plate_number' => 'B 1234 GP', 'capacity' => 40, 'bus_class' => 'Executive'],
            ['name' => 'Nusantara Jaya', 'plate_number' => 'B 9876 NJ', 'capacity' => 36, 'bus_class' => 'Business'],
            ['name' => 'Langit Biru', 'plate_number' => 'D 4466 LB', 'capacity' => 32, 'bus_class' => 'VIP'],
            ['name' => 'Safari Sentosa', 'plate_number' => 'L 7788 SS', 'capacity' => 40, 'bus_class' => 'Executive'],
            ['name' => 'Bali Dwipa', 'plate_number' => 'DK 2233 BD', 'capacity' => 28, 'bus_class' => 'Premium'],
        ];

        foreach ($buses as $bus) {
            Bus::updateOrCreate(
                ['plate_number' => $bus['plate_number']],
                [
                    'name' => $bus['name'],
                    'capacity' => $bus['capacity'],
                    'bus_class' => $bus['bus_class'],
                    'is_active' => true,
                ]
            );
        }
    }
}
