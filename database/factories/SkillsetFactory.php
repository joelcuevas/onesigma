<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillsetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fn = fn ($x) => sqrt(1 - $x);

        return [
            'date' => now(),
            's0' => fake()->biasedNumberBetween(1, 5, $fn),
            's1' => fake()->biasedNumberBetween(1, 5, $fn),
            's2' => fake()->biasedNumberBetween(1, 5, $fn),
            's3' => fake()->biasedNumberBetween(1, 5, $fn),
            's4' => fake()->biasedNumberBetween(1, 5, $fn),
            's5' => fake()->biasedNumberBetween(1, 5, $fn),
            's6' => fake()->biasedNumberBetween(1, 5, $fn),
            's7' => fake()->biasedNumberBetween(1, 5, $fn),
            's8' => fake()->biasedNumberBetween(1, 5, $fn),
            's9' => fake()->biasedNumberBetween(1, 5, $fn),
        ];
    }

    public function se1()
    {
        return $this->state(function (array $attributes) {
            return [
                's0' => 1,
                's1' => 1,
                's2' => 1,
                's3' => 1,
                's4' => 1,
                's5' => 1,
                's6' => 1,
                's7' => 1,
                's8' => 1,
                's9' => 1,
            ];
        });
    }

    public function se7()
    {
        return $this->state(function (array $attributes) {
            return [
                's0' => 5,
                's1' => 5,
                's2' => 5,
                's3' => 5,
                's4' => 5,
                's5' => 5,
                's6' => 5,
                's7' => 5,
                's8' => 5,
                's9' => 5,
            ];
        });
    }
}
