<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\WhatsAppLogController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;

// Rute publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'showSearchForm'])->name('search.form');
Route::post('/search', [HomeController::class, 'search'])->name('search.trips');
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show')->where('trip', '[0-9]+');

// Autentikasi
require __DIR__.'/auth.php';

// Rute yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard umum (akan diarahkan berdasarkan role) - perlu force phone
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('force.phone');

    // Rute untuk user biasa - perlu force phone
    Route::middleware('force.phone')->prefix('user')->name('user.')->group(function () {
        Route::resource('bookings', UserBookingController::class)->only(['index', 'show']);
    });

    // Rute untuk membuat booking (hanya untuk user biasa) - perlu force phone
    Route::middleware('force.phone')->get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::middleware(['force.phone', 'throttle:5,1'])->post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::middleware('force.phone')->get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
    Route::middleware('force.phone')->get('/bookings/{booking}/ticket', [BookingController::class, 'downloadTicket'])->name('bookings.ticket');
    Route::middleware('force.phone')->post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // API untuk seat status (public untuk authenticated users)
    Route::middleware('force.phone')->get('/api/trips/{trip}/seats', [SeatController::class, 'getSeats'])->name('api.trips.seats');
    Route::middleware('force.phone')->get('/api/trips/{trip}/seats/recommend', [SeatController::class, 'getRecommendedSeats'])->name('api.trips.seats.recommend');

    // ============================================
    // RUTE ADMIN - Group dengan middleware admin
    // ============================================
    Route::middleware(['admin', 'force.phone'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Dashboard Admin
            // (sudah dihandle oleh DashboardController@index berdasarkan role)

            // CRUD Bus
            Route::resource('buses', BusController::class);
            Route::get('buses/{bus}/seats', [\App\Http\Controllers\BusSeatController::class, 'edit'])->name('buses.seats.edit');
            Route::put('buses/{bus}/seats', [\App\Http\Controllers\BusSeatController::class, 'update'])->name('buses.seats.update');

            // CRUD Route
            Route::resource('routes', RouteController::class);

            // CRUD Trip
            Route::resource('trips', TripController::class);
            Route::get('trips/export/csv', [TripController::class, 'exportCsv'])->name('trips.export.csv');

            // CRUD Booking
            Route::resource('bookings', BookingController::class);
            Route::get('bookings/export/csv', [BookingController::class, 'exportCsv'])->name('bookings.export.csv');

            // WhatsApp Logs
            Route::get('whatsapp-logs', [WhatsAppLogController::class, 'index'])->name('whatsapp-logs.index');
        });
});
