<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Bus',
            'plate_number' => $this->faker->regexify('[A-Z]{2,3}[0-9]{3,4}[A-Z]{1,3}'),
            'capacity' => $this->faker->numberBetween(20, 60),
            'bus_class' => $this->faker->randomElement(['Ekonomi', 'Bisnis', 'Eksekutif']),
            'is_active' => $this->faker->boolean(90), // 90% chance to be active
        ];
    }
}
