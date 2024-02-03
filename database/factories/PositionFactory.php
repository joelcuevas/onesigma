<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $level = fake()->unique()->numberBetween(1, 1000000);

        return [
            'type' => 'engineer',
            'code' => 'SE'.$level,
            'track' => 'SE',
            'level' => $level,
            'title' => 'Software Engineer '.$level,
            's0' => rand(1, 5),
            's1' => rand(1, 5),
            's2' => rand(1, 5),
            's3' => rand(1, 5),
            's4' => rand(1, 5),
            's5' => rand(1, 5),
            's6' => rand(1, 5),
            's7' => rand(1, 5),
            's8' => rand(1, 5),
            's9' => rand(1, 5),
        ];
    }

    public function track()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'engineer',
                'code' => 'SE',
                'level' => 0,
            ];
        });
    }
}
