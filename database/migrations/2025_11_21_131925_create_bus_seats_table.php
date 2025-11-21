<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel untuk menyimpan layout kursi per bus
     */
    public function up(): void
    {
        Schema::create('bus_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            $table->string('seat_number', 10); // e.g., "A1", "B3", "1", "2"
            $table->integer('row_index')->default(0); // Baris (0-based)
            $table->integer('col_index')->default(0); // Kolom (0-based)
            $table->string('deck', 20)->nullable(); // "upper", "lower", null
            $table->string('section', 20)->nullable(); // "front", "middle", "back", null
            $table->decimal('default_price', 10, 2)->nullable(); // Harga default kursi ini (optional)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk query cepat
            $table->index('bus_id');
            $table->index(['bus_id', 'row_index', 'col_index']);
            $table->unique(['bus_id', 'seat_number'], 'unique_bus_seat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_seats');
    }
};
