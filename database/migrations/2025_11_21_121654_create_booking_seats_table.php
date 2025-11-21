<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pendekatan: Tabel terpisah untuk booking_seats
     * Kelebihan:
     * - Tracking individual seat lebih detail
     * - Mudah untuk fitur seat pricing berbeda per kursi
     * - Query dan reporting lebih mudah
     * - Rollback lebih mudah jika booking dibatalkan
     *
     * Kekurangan:
     * - Satu tabel tambahan
     * - Join query sedikit lebih kompleks
     */
    public function up(): void
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->string('seat_number', 10); // e.g., "A1", "B3"
            $table->decimal('seat_price', 10, 2)->default(0); // Harga per kursi (bisa berbeda)
            $table->timestamps();

            // Unique constraint: satu kursi tidak bisa dibooking dua kali untuk trip yang sama
            $table->unique(['trip_id', 'seat_number'], 'unique_trip_seat');

            // Index untuk query cepat
            $table->index('trip_id');
            $table->index('booking_id');
            $table->index(['trip_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};
