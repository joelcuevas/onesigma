<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Position;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->colorName(),
            'position_id' => Position::factory(),
        ];
    }

    public function st3(): static
    {
        return $this->state(fn (array $attributes) => [
           'position_id' => Position::firstWhere('track', 'ST3')->id,
        ]);
    }

    public function cluster(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_cluster' => true,
        ]);
    }
}
