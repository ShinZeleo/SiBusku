<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'route_id' => function () {
                return Route::factory()->create()->id;
            },
            'bus_id' => function () {
                return Bus::factory()->create()->id;
            },
            'departure_date' => $this->faker->date('Y-m-d', '+30 days'),
            'departure_time' => $this->faker->time('H:i'),
            'price' => $this->faker->numberBetween(50000, 500000),
            'total_seats' => $this->faker->numberBetween(30, 60),
            'available_seats' => function (array $attributes) {
                // Jika status bukan scheduled, available_seats mungkin berbeda
                if ($attributes['status'] === 'scheduled') {
                    return $attributes['total_seats'];
                }
                return $this->faker->numberBetween(0, $attributes['total_seats']);
            },
            'status' => $this->faker->randomElement(['scheduled', 'running', 'completed', 'cancelled']),
        ];
    }
}
