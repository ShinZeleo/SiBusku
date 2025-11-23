<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            // Executive Class
            ['name' => 'Garuda Prima', 'plate_number' => 'B 1234 GP', 'capacity' => 40, 'bus_class' => 'Executive'],
            ['name' => 'Safari Sentosa', 'plate_number' => 'L 7788 SS', 'capacity' => 40, 'bus_class' => 'Executive'],
            ['name' => 'Sinar Jaya', 'plate_number' => 'B 2345 SJ', 'capacity' => 40, 'bus_class' => 'Executive'],
            ['name' => 'Lorena', 'plate_number' => 'B 3456 LR', 'capacity' => 40, 'bus_class' => 'Executive'],
            ['name' => 'Pahala Kencana', 'plate_number' => 'B 4567 PK', 'capacity' => 40, 'bus_class' => 'Executive'],

            // Business Class
            ['name' => 'Nusantara Jaya', 'plate_number' => 'B 9876 NJ', 'capacity' => 36, 'bus_class' => 'Business'],
            ['name' => 'Rosalia Indah', 'plate_number' => 'B 5678 RI', 'capacity' => 36, 'bus_class' => 'Business'],
            ['name' => 'Haryanto', 'plate_number' => 'B 6789 HR', 'capacity' => 36, 'bus_class' => 'Business'],
            ['name' => 'Efisiensi', 'plate_number' => 'B 7890 EF', 'capacity' => 36, 'bus_class' => 'Business'],

            // VIP Class
            ['name' => 'Langit Biru', 'plate_number' => 'D 4466 LB', 'capacity' => 32, 'bus_class' => 'VIP'],
            ['name' => 'Sumber Alam', 'plate_number' => 'B 8901 SA', 'capacity' => 32, 'bus_class' => 'VIP'],
            ['name' => 'Kramat Djati', 'plate_number' => 'B 9012 KD', 'capacity' => 32, 'bus_class' => 'VIP'],

            // Premium Class
            ['name' => 'Bali Dwipa', 'plate_number' => 'DK 2233 BD', 'capacity' => 28, 'bus_class' => 'Premium'],
            ['name' => 'Eka', 'plate_number' => 'B 0123 EK', 'capacity' => 28, 'bus_class' => 'Premium'],
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
