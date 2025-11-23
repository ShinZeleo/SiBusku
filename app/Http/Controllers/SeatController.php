<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Services\SeatRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controller untuk API endpoint terkait kursi
 *
 * Controller ini menyediakan API endpoint untuk mendapatkan informasi
 * kursi dan rekomendasi kursi. Endpoint ini digunakan oleh frontend
 * untuk menampilkan seat picker dan fitur rekomendasi kursi.
 *
 * @package App\Http\Controllers
 */
class SeatController extends Controller
{
    /**
     * Mendapatkan informasi kursi untuk trip tertentu
     *
     * Endpoint API ini mengembalikan data lengkap tentang kursi dalam trip,
     * termasuk layout kursi, status ketersediaan, dan statistik kursi.
     *
     * Data yang dikembalikan:
     * - trip_id: ID trip
     * - total_seats: Total kursi dalam trip
     * - available_count: Jumlah kursi yang tersedia
     * - booked_count: Jumlah kursi yang sudah dibooking
     * - layout: Array kursi dengan informasi:
     *   - seat_number: Nomor kursi (contoh: 'A1', 'B3')
     *   - row_index: Index baris (0-based)
     *   - col_index: Index kolom (0-based)
     *   - section: Section kursi (front, middle, back)
     *   - status: Status kursi ('available' atau 'booked')
     *
     * Endpoint: GET /api/trips/{trip}/seats
     *
     * @param Request $request HTTP request (tidak digunakan, hanya untuk consistency)
     * @param Trip $trip Trip yang akan dicek kursinya (route model binding)
     * @return JsonResponse JSON response dengan data kursi
     */
    public function getSeats(Request $request, Trip $trip): JsonResponse
    {
        $seatLayout = $trip->getSeatLayoutForPicker();
        $bookedSeats = $trip->booked_seats;
        $availableSeats = $trip->available_seat_numbers;

        // Buat map seat_number -> status
        $seatStatusMap = [];
        foreach ($seatLayout as $seat) {
            $seatNumber = $seat['seat_number'];
            $seatStatusMap[$seatNumber] = [
                'seat_number' => $seatNumber,
                'row_index' => $seat['row_index'],
                'col_index' => $seat['col_index'],
                'section' => $seat['section'],
                'status' => in_array($seatNumber, $bookedSeats) ? 'booked' : 'available',
            ];
        }

        return response()->json([
            'trip_id' => $trip->id,
            'total_seats' => $trip->total_seats,
            'available_count' => count($availableSeats),
            'booked_count' => count($bookedSeats),
            'layout' => array_values($seatStatusMap),
        ]);
    }

    /**
     * Mendapatkan rekomendasi kursi terbaik untuk user
     *
     * Endpoint API ini mengembalikan rekomendasi kursi terbaik berdasarkan
     * algoritma SeatRecommendationService. Rekomendasi mempertimbangkan:
     * - Posisi kursi (front lebih diprioritaskan)
     * - Kursi yang tersedia
     * - Jumlah kursi yang diminta
     *
     * Query Parameter:
     * - count: Jumlah kursi yang direkomendasikan (default: 1, max: available_seats)
     *
     * Data yang dikembalikan:
     * - trip_id: ID trip
     * - count: Jumlah kursi yang direkomendasikan
     * - recommended_seats: Array nomor kursi yang direkomendasikan
     *
     * Endpoint: GET /api/trips/{trip}/seats/recommend?count=2
     *
     * @param Request $request HTTP request dengan query parameter 'count'
     * @param Trip $trip Trip yang akan direkomendasikan kursinya (route model binding)
     * @return JsonResponse JSON response dengan rekomendasi kursi
     */
    public function getRecommendedSeats(Request $request, Trip $trip): JsonResponse
    {
        $count = (int) $request->query('count', 1);
        // Batasi sesuai dengan kursi yang tersedia di trip
        $maxAvailable = $trip->available_seats;
        $count = max(1, min($maxAvailable, $count));

        $recommended = SeatRecommendationService::recommendSeats($trip, $count);

        return response()->json([
            'trip_id' => $trip->id,
            'count' => $count,
            'recommended_seats' => $recommended,
        ]);
    }
}

