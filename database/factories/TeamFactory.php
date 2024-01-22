<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'track' => 'ST3',
        ];
    }

    public function cluster(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_cluster' => true,
        ]);
    }
}
