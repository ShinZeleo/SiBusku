<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'origin_city' => $this->faker->city,
            'destination_city' => $this->faker->city,
            'duration_estimate' => $this->faker->randomFloat(2, 1, 12), // 1-12 hours
            'is_active' => $this->faker->boolean(95), // 95% chance to be active
        ];
    }
}
