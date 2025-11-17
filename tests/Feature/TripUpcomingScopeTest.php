<?php

namespace Tests\Feature;

use App\Models\Trip;
use App\Models\Route;
use App\Models\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripUpcomingScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_trip_upcoming_scope_returns_correct_trips()
    {
        $route = Route::factory()->create();
        $bus = Bus::factory()->create();

        // Membuat trip dengan status scheduled untuk hari ini (harus muncul di upcoming)
        $todayTrip = Trip::factory()->create([
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_date' => now()->format('Y-m-d'),
            'status' => 'scheduled',
        ]);

        // Membuat trip dengan status scheduled untuk masa depan (harus muncul di upcoming)
        $futureTrip = Trip::factory()->create([
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_date' => now()->addDays(1)->format('Y-m-d'),
            'status' => 'scheduled',
        ]);

        // Membuat trip dengan status cancelled (tidak boleh muncul di upcoming)
        $cancelledTrip = Trip::factory()->create([
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_date' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'cancelled',
        ]);

        // Membuat trip dengan status completed (tidak boleh muncul di upcoming)
        $completedTrip = Trip::factory()->create([
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_date' => now()->subDays(1)->format('Y-m-d'), // kemarin
            'status' => 'completed',
        ]);

        // Menggunakan scope upcoming
        $upcomingTrips = Trip::upcoming()->get();

        // Memastikan hanya trip dengan status scheduled dan tanggal >= hari ini yang muncul
        $this->assertCount(2, $upcomingTrips);
        $this->assertTrue($upcomingTrips->contains($todayTrip));
        $this->assertTrue($upcomingTrips->contains($futureTrip));
        $this->assertFalse($upcomingTrips->contains($cancelledTrip));
        $this->assertFalse($upcomingTrips->contains($completedTrip));
    }
}
