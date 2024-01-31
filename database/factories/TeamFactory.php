<?php

namespace Database\Factories;

use App\Models\Position;
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
            'name' => ucfirst(fake()->unique()->domainWord().rand(1, 9)),
            'position_id' => Position::factory(),
        ];
    }

    public function st3(): static
    {
        return $this->state(fn (array $attributes) => [
            'position_id' => Position::firstWhere('code', 'ST3')->id,
        ]);
    }

    public function cluster(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_cluster' => true,
        ]);
    }
}
