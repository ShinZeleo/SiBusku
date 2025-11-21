<?php

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user tidak bisa booking kursi yang sudah dipakai', function () {
    // Setup: Buat user, bus, route, dan trip
    $user1 = User::factory()->create(['role' => 'user']);
    $user2 = User::factory()->create(['role' => 'user']);

    $bus = Bus::factory()->create(['capacity' => 32]);
    $route = Route::factory()->create();
    $trip = Trip::factory()->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'total_seats' => 32,
        'available_seats' => 32,
    ]);

    // User1 booking kursi A1
    $bookingService = app(BookingService::class);
    $booking1 = $bookingService->createBooking([
        'user_id' => $user1->id,
        'trip_id' => $trip->id,
        'customer_name' => $user1->name,
        'customer_phone' => $user1->phone,
        'selected_seats' => 'A1',
    ]);

    // Pastikan booking1 berhasil
    expect($booking1)->toBeInstanceOf(Booking::class);
    expect($booking1->selected_seats)->toContain('A1');

    // User2 mencoba booking kursi A1 yang sama - harus gagal
    $booking2Created = false;
    try {
        $booking2 = $bookingService->createBooking([
            'user_id' => $user2->id,
            'trip_id' => $trip->id,
            'customer_name' => $user2->name,
            'customer_phone' => $user2->phone,
            'selected_seats' => 'A1', // Kursi yang sama
        ]);
        $booking2Created = true;
    } catch (\Exception $e) {
        // Expected - booking harus gagal
        expect($e)->toBeInstanceOf(\Exception::class);
    }

    // Pastikan booking2 tidak berhasil dibuat
    expect($booking2Created)->toBeFalse();

    // Pastikan hanya ada 1 booking untuk kursi A1
    $bookedSeats = BookingSeat::where('trip_id', $trip->id)
        ->where('seat_number', 'A1')
        ->count();

    expect($bookedSeats)->toBe(1);
});

test('dua user bisa booking kursi berbeda untuk trip yang sama', function () {
    $user1 = User::factory()->create(['role' => 'user']);
    $user2 = User::factory()->create(['role' => 'user']);

    $bus = Bus::factory()->create(['capacity' => 32]);
    $route = Route::factory()->create();
    $trip = Trip::factory()->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'total_seats' => 32,
        'available_seats' => 32,
    ]);

    $bookingService = app(BookingService::class);

    // User1 booking kursi A1
    $booking1 = $bookingService->createBooking([
        'user_id' => $user1->id,
        'trip_id' => $trip->id,
        'customer_name' => $user1->name,
        'customer_phone' => $user1->phone,
        'selected_seats' => 'A1',
    ]);

    // User2 booking kursi A2 (berbeda)
    $booking2 = $bookingService->createBooking([
        'user_id' => $user2->id,
        'trip_id' => $trip->id,
        'customer_name' => $user2->name,
        'customer_phone' => $user2->phone,
        'selected_seats' => 'A2',
    ]);

    // Kedua booking harus berhasil
    expect($booking1)->toBeInstanceOf(Booking::class);
    expect($booking2)->toBeInstanceOf(Booking::class);

    expect($booking1->selected_seats)->toContain('A1');
    expect($booking2->selected_seats)->toContain('A2');
});
