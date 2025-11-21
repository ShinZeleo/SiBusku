<?php

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user hanya bisa cancel booking miliknya yang masih pending', function () {
    // Setup
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

    // User1 membuat booking (status pending)
    $booking1 = $bookingService->createBooking([
        'user_id' => $user1->id,
        'trip_id' => $trip->id,
        'customer_name' => $user1->name,
        'customer_phone' => $user1->phone,
        'selected_seats' => 'A1',
    ]);

    // User1 bisa cancel booking miliknya
    $exceptionThrown = false;
    try {
        $bookingService->cancelBookingByUser($booking1, $user1->id);
    } catch (\Exception $e) {
        $exceptionThrown = true;
    }
    expect($exceptionThrown)->toBeFalse();

    // Refresh booking dari database
    $booking1->refresh();
    expect($booking1->status)->toBe('cancelled');

    // User2 tidak bisa cancel booking milik user1
    $booking2 = $bookingService->createBooking([
        'user_id' => $user2->id,
        'trip_id' => $trip->id,
        'customer_name' => $user2->name,
        'customer_phone' => $user2->phone,
        'selected_seats' => 'A2',
    ]);

    expect(function () use ($bookingService, $booking2, $user1) {
        $bookingService->cancelBookingByUser($booking2, $user1->id);
    })->toThrow(\Illuminate\Auth\Access\AuthorizationException::class);
});

test('user tidak bisa cancel booking yang sudah confirmed', function () {
    $user = User::factory()->create(['role' => 'user']);

    $bus = Bus::factory()->create(['capacity' => 32]);
    $route = Route::factory()->create();
    $trip = Trip::factory()->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'total_seats' => 32,
        'available_seats' => 32,
    ]);

    $bookingService = app(BookingService::class);

    // Buat booking
    $booking = $bookingService->createBooking([
        'user_id' => $user->id,
        'trip_id' => $trip->id,
        'customer_name' => $user->name,
        'customer_phone' => $user->phone,
        'selected_seats' => 'A1',
    ]);

    // Ubah status menjadi confirmed
    $booking->update(['status' => 'confirmed']);

    // User tidak bisa cancel booking yang sudah confirmed
    $exceptionThrown = false;
    try {
        $bookingService->cancelBookingByUser($booking, $user->id);
    } catch (\Exception $e) {
        $exceptionThrown = true;
        expect($e)->toBeInstanceOf(\Exception::class);
    }
    expect($exceptionThrown)->toBeTrue();
});

test('kursi dikembalikan saat booking dibatalkan', function () {
    $user = User::factory()->create(['role' => 'user']);

    $bus = Bus::factory()->create(['capacity' => 32]);
    $route = Route::factory()->create();
    $trip = Trip::factory()->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'total_seats' => 32,
        'available_seats' => 32,
    ]);

    $bookingService = app(BookingService::class);

    // Buat booking
    $booking = $bookingService->createBooking([
        'user_id' => $user->id,
        'trip_id' => $trip->id,
        'customer_name' => $user->name,
        'customer_phone' => $user->phone,
        'selected_seats' => 'A1',
    ]);

    // Pastikan available_seats berkurang
    $trip->refresh();
    expect($trip->available_seats)->toBe(31);

    // Cancel booking
    $bookingService->cancelBookingByUser($booking, $user->id);

    // Pastikan available_seats kembali
    $trip->refresh();
    expect($trip->available_seats)->toBe(32);
});
