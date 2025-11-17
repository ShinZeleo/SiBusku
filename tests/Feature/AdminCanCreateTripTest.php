<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCanCreateTripTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_trip()
    {
        // Membuat user admin
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        // Membuat bus dan route untuk trip
        $bus = Bus::factory()->create();
        $route = Route::factory()->create();

        // Login sebagai admin
        $this->actingAs($admin);

        // Data trip yang akan dibuat
        $tripData = [
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_date' => now()->addDays(7)->format('Y-m-d'),
            'departure_time' => '08:00',
            'price' => 150000,
            'total_seats' => 40,
        ];

        // Submit form untuk membuat trip
        $response = $this->post('/admin/trips', $tripData);

        // Harus redirect ke index trip dengan pesan sukses
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Memastikan trip berhasil dibuat di database
        $this->assertDatabaseHas('trips', [
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'price' => 150000,
            'total_seats' => 40,
            'available_seats' => 40, // Karena baru dibuat, semua kursi tersedia
        ]);
    }
}
