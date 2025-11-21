<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Services\SeatRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    /**
     * Get available seats for a trip dengan layout dari database
     * Endpoint: GET /api/trips/{trip}/seats
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
     * Get recommended seats
     * Endpoint: GET /api/trips/{trip}/seats/recommend?count=2
     */
    public function getRecommendedSeats(Request $request, Trip $trip): JsonResponse
    {
        $count = (int) $request->query('count', 1);
        $count = max(1, min(4, $count)); // Batasi 1-4 kursi

        $recommended = SeatRecommendationService::recommendSeats($trip, $count);

        return response()->json([
            'trip_id' => $trip->id,
            'count' => $count,
            'recommended_seats' => $recommended,
        ]);
    }
}

