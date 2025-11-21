<?php

namespace App\Services;

use App\Models\Trip;

class SeatRecommendationService
{
    /**
     * Rekomendasi kursi terbaik berdasarkan strategi:
     * 1. Pilih kursi di bagian tengah bus (hindari paling depan dan belakang)
     * 2. Jika lebih dari 1 kursi, usahakan bersebelahan
     * 3. Pilih kursi yang tidak di pinggir (jika memungkinkan)
     */
    public static function recommendSeats(Trip $trip, int $count = 1): array
    {
        $availableSeats = $trip->available_seat_numbers;
        $seatLayout = $trip->getSeatLayoutForPicker();
        $bookedSeats = $trip->booked_seats;

        if (empty($availableSeats) || count($availableSeats) < $count) {
            return [];
        }

        // Buat map seat_number -> layout info
        $layoutMap = [];
        foreach ($seatLayout as $seat) {
            $layoutMap[$seat['seat_number']] = $seat;
        }

        // Filter hanya kursi yang tersedia
        $availableLayout = array_filter($seatLayout, function ($seat) use ($availableSeats) {
            return in_array($seat['seat_number'], $availableSeats);
        });

        if (count($availableLayout) < $count) {
            return [];
        }

        // Jika hanya butuh 1 kursi, pilih yang paling tengah
        if ($count === 1) {
            return [self::findBestSingleSeat($availableLayout)];
        }

        // Jika butuh lebih dari 1, cari yang bersebelahan
        return self::findAdjacentSeats($availableLayout, $count, $layoutMap);
    }

    /**
     * Cari kursi terbaik untuk 1 penumpang
     */
    private static function findBestSingleSeat(array $availableLayout): string
    {
        // Hitung total baris
        $maxRow = max(array_column($availableLayout, 'row_index'));
        $maxCol = max(array_column($availableLayout, 'col_index'));

        // Target: tengah bus (hindari 20% pertama dan 20% terakhir)
        $targetRowStart = (int) ($maxRow * 0.2);
        $targetRowEnd = (int) ($maxRow * 0.8);
        $targetCol = (int) ($maxCol / 2); // Tengah kolom

        $bestSeat = null;
        $bestScore = -1;

        foreach ($availableLayout as $seat) {
            $row = $seat['row_index'];
            $col = $seat['col_index'];

            // Skor berdasarkan:
            // 1. Jarak dari tengah baris (semakin tengah semakin baik)
            // 2. Jarak dari tengah kolom
            // 3. Hindari baris pertama dan terakhir

            $rowScore = 0;
            if ($row >= $targetRowStart && $row <= $targetRowEnd) {
                $rowScore = 10; // Di zona tengah
            } elseif ($row < $targetRowStart) {
                $rowScore = 5 - ($targetRowStart - $row); // Semakin jauh dari tengah, semakin kecil
            } else {
                $rowScore = 5 - ($row - $targetRowEnd);
            }

            $colScore = 10 - abs($col - $targetCol); // Semakin tengah kolom semakin baik

            // Hindari baris pertama dan terakhir
            $edgePenalty = 0;
            if ($row === 0 || $row === $maxRow) {
                $edgePenalty = -3;
            }

            $totalScore = $rowScore + $colScore + $edgePenalty;

            if ($totalScore > $bestScore) {
                $bestScore = $totalScore;
                $bestSeat = $seat['seat_number'];
            }
        }

        return $bestSeat ?? $availableLayout[0]['seat_number'];
    }

    /**
     * Cari kursi bersebelahan untuk multiple penumpang
     */
    private static function findAdjacentSeats(array $availableLayout, int $count, array $layoutMap): array
    {
        // Group by row
        $seatsByRow = [];
        foreach ($availableLayout as $seat) {
            $row = $seat['row_index'];
            if (!isset($seatsByRow[$row])) {
                $seatsByRow[$row] = [];
            }
            $seatsByRow[$row][] = $seat;
        }

        // Cari baris yang punya cukup kursi bersebelahan
        foreach ($seatsByRow as $row => $seats) {
            // Sort by col_index
            usort($seats, function ($a, $b) {
                return $a['col_index'] <=> $b['col_index'];
            });

            // Cari sequence bersebelahan
            for ($i = 0; $i <= count($seats) - $count; $i++) {
                $sequence = array_slice($seats, $i, $count);

                // Cek apakah bersebelahan
                $isAdjacent = true;
                for ($j = 1; $j < count($sequence); $j++) {
                    if ($sequence[$j]['col_index'] - $sequence[$j-1]['col_index'] !== 1) {
                        $isAdjacent = false;
                        break;
                    }
                }

                if ($isAdjacent) {
                    return array_column($sequence, 'seat_number');
                }
            }
        }

        // Jika tidak ada yang bersebelahan, ambil yang terdekat
        // Ambil dari tengah bus
        $maxRow = max(array_column($availableLayout, 'row_index'));
        $targetRow = (int) ($maxRow / 2);

        $seatsInTargetRow = array_filter($availableLayout, function ($seat) use ($targetRow) {
            return $seat['row_index'] === $targetRow;
        });

        if (count($seatsInTargetRow) >= $count) {
            $seatsInTargetRow = array_slice($seatsInTargetRow, 0, $count);
            return array_column($seatsInTargetRow, 'seat_number');
        }

        // Fallback: ambil $count kursi pertama yang tersedia
        return array_slice(array_column($availableLayout, 'seat_number'), 0, $count);
    }
}

