<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserBookingController;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Route;

// Rute publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'showSearchForm'])->name('search.form');
Route::post('/search', [HomeController::class, 'search'])->name('search.trips');

// Route untuk testing WA - hanya untuk development
Route::get('/tes-wa', function () {
    $result = WhatsAppService::send(config('services.fonnte.admin_phone', '62895802990864'), 'Tes Fonnte dari SIBUSKU');
    return $result ? 'WA terkirim' : 'WA gagal';
})->name('test.wa');

// Route debugging untuk melihat response langsung dari Fonnte
Route::get('/debug-wa', function () {
    $url = config('services.fonnte.url');
    $token = config('services.fonnte.token');
    $phone = '62895802990864'; // Gunakan nomor aktif
    $message = 'Debug Fonnte dari SIBUSKU';
    $countryCode = config('services.fonnte.country_code', '62');

    // Normalisasi nomor
    $phone = preg_replace('/[^0-9]/', '', $phone);

    $payload = [
        'target' => $phone,
        'message' => $message,
        'countryCode' => $countryCode,
    ];

    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
        ])->asMultipart()->post($url, $payload);

        return [
            'status' => $response->successful() ? 'success' : 'failed',
            'status_code' => $response->status(),
            'body' => $response->json() ?: $response->body(),
            'payload' => $payload
        ];
    } catch (\Throwable $e) {
        return [
            'status' => 'error',
            'error' => $e->getMessage(),
            'payload' => $payload
        ];
    }
});

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

    // Rute untuk admin - perlu force phone
    Route::middleware(['admin', 'force.phone'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('buses', BusController::class);
        Route::resource('routes', RouteController::class);
        Route::resource('trips', TripController::class);
        Route::resource('bookings', BookingController::class);
        Route::get('bookings/export/csv', [BookingController::class, 'exportCsv'])->name('bookings.export.csv');
        Route::get('trips/export/csv', [TripController::class, 'exportCsv'])->name('trips.export.csv');
    });

    // Rute untuk membuat booking (hanya untuk user biasa) - perlu force phone
    Route::middleware('force.phone')->post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});
