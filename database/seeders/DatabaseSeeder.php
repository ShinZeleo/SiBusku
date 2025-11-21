<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\BusSeat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users (gunakan updateOrCreate untuk menghindari duplicate)
        $admin = User::updateOrCreate(
            ['email' => 'admin@sibusku.com'],
            [
                'name' => 'Admin SIBUSKU',
                'password' => Hash::make('password'),
                'phone' => '6281234567890',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $user1 = User::updateOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'phone' => '6281234567891',
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        $user2 = User::updateOrCreate(
            ['email' => 'siti@example.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'phone' => '6281234567892',
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Buses dengan Seat Layout (gunakan updateOrCreate)
        $bus1 = Bus::updateOrCreate(
            ['plate_number' => 'B 1234 ABC'],
            [
                'name' => 'Sinar Jaya',
                'bus_class' => 'Eksekutif',
                'capacity' => 32,
            ]
        );

        $bus2 = Bus::updateOrCreate(
            ['plate_number' => 'B 5678 DEF'],
            [
                'name' => 'Lorena',
                'bus_class' => 'Bisnis',
                'capacity' => 40,
            ]
        );

        $bus3 = Bus::updateOrCreate(
            ['plate_number' => 'B 9012 GHI'],
            [
                'name' => 'Pahala Kencana',
                'bus_class' => 'Eksekutif',
                'capacity' => 36,
            ]
        );

        // Create seat layout untuk bus1 (32 kursi: 8 rows x 4 cols)
        // Hapus seat lama jika ada, lalu buat baru
        $bus1->seats()->delete();
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($rows as $rowIndex => $row) {
            for ($col = 1; $col <= 4; $col++) {
                BusSeat::create([
                    'bus_id' => $bus1->id,
                    'seat_number' => $row . $col,
                    'row_index' => $rowIndex,
                    'col_index' => $col - 1,
                    'section' => $rowIndex < 2 ? 'front' : ($rowIndex < 6 ? 'middle' : 'back'),
                    'is_active' => true,
                ]);
            }
        }

        // Create seat layout untuk bus2 (40 kursi: 10 rows x 4 cols)
        $bus2->seats()->delete();
        $rows2 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        foreach ($rows2 as $rowIndex => $row) {
            for ($col = 1; $col <= 4; $col++) {
                BusSeat::create([
                    'bus_id' => $bus2->id,
                    'seat_number' => $row . $col,
                    'row_index' => $rowIndex,
                    'col_index' => $col - 1,
                    'section' => $rowIndex < 2 ? 'front' : ($rowIndex < 8 ? 'middle' : 'back'),
                    'is_active' => true,
                ]);
            }
        }

        // Create seat layout untuk bus3 (36 kursi: 9 rows x 4 cols)
        $bus3->seats()->delete();
        $rows3 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        foreach ($rows3 as $rowIndex => $row) {
            for ($col = 1; $col <= 4; $col++) {
                BusSeat::create([
                    'bus_id' => $bus3->id,
                    'seat_number' => $row . $col,
                    'row_index' => $rowIndex,
                    'col_index' => $col - 1,
                    'section' => $rowIndex < 2 ? 'front' : ($rowIndex < 7 ? 'middle' : 'back'),
                    'is_active' => true,
                ]);
            }
        }

        // 3. Create Routes (gunakan updateOrCreate)
        $route1 = Route::updateOrCreate(
            [
                'origin_city' => 'Jakarta',
                'destination_city' => 'Bandung',
            ],
            [
                'duration_estimate' => 3,
                'is_active' => true,
            ]
        );

        $route2 = Route::updateOrCreate(
            [
                'origin_city' => 'Jakarta',
                'destination_city' => 'Yogyakarta',
            ],
            [
                'duration_estimate' => 10,
                'is_active' => true,
            ]
        );

        $route3 = Route::updateOrCreate(
            [
                'origin_city' => 'Bandung',
                'destination_city' => 'Yogyakarta',
            ],
            [
                'duration_estimate' => 7,
                'is_active' => true,
            ]
        );

        $route4 = Route::updateOrCreate(
            [
                'origin_city' => 'Jakarta',
                'destination_city' => 'Surabaya',
            ],
            [
                'duration_estimate' => 14,
                'is_active' => true,
            ]
        );

        // 4. Create Trips
        $trips = [];
        $dates = [
            now()->addDays(1),
            now()->addDays(2),
            now()->addDays(3),
            now()->addDays(4),
            now()->addDays(5),
        ];

        // Trip Jakarta - Bandung
        foreach ($dates as $index => $date) {
            $trips[] = Trip::create([
                'route_id' => $route1->id,
                'bus_id' => $bus1->id,
                'departure_date' => $date->format('Y-m-d'),
                'departure_time' => '08:00',
                'price' => 150000,
                'total_seats' => 32,
                'available_seats' => 32,
                'status' => 'scheduled',
            ]);

            $trips[] = Trip::create([
                'route_id' => $route1->id,
                'bus_id' => $bus2->id,
                'departure_date' => $date->format('Y-m-d'),
                'departure_time' => '14:00',
                'price' => 120000,
                'total_seats' => 40,
                'available_seats' => 40,
                'status' => 'scheduled',
            ]);
        }

        // Trip Jakarta - Yogyakarta
        foreach ($dates as $date) {
            $trips[] = Trip::create([
                'route_id' => $route2->id,
                'bus_id' => $bus3->id,
                'departure_date' => $date->format('Y-m-d'),
                'departure_time' => '20:00',
                'price' => 300000,
                'total_seats' => 36,
                'available_seats' => 36,
                'status' => 'scheduled',
            ]);
        }

        // 5. Create Sample Bookings
        if (count($trips) > 0) {
            // Booking 1: User1 booking trip pertama (Jakarta-Bandung)
            $booking1 = Booking::create([
                'user_id' => $user1->id,
                'trip_id' => $trips[0]->id,
                'customer_name' => $user1->name,
                'customer_phone' => $user1->phone,
                'seats_count' => 2,
                'selected_seats' => 'A1, A2',
                'total_price' => 300000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            // Update trip available seats
            $trips[0]->decrement('available_seats', 2);

            // Booking 2: User2 booking trip kedua
            $booking2 = Booking::create([
                'user_id' => $user2->id,
                'trip_id' => $trips[1]->id,
                'customer_name' => $user2->name,
                'customer_phone' => $user2->phone,
                'seats_count' => 1,
                'selected_seats' => 'B3',
                'total_price' => 150000,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $trips[1]->decrement('available_seats', 1);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@sibusku.com / password');
        $this->command->info('User1: budi@example.com / password');
        $this->command->info('User2: siti@example.com / password');
    }
}
