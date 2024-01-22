<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Metric>
 */
class MetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'metric' => 'commits',
            'value' => fake()->randomFloat(2, 0, 5),
            'date' => fake()->dateTimeBetween('-1 week', 'now'),
            'source' => 'computed',
            'context' => [],
        ];
    }

    public function set($name, $value = null, $source = 'computed')
    {
        return $this->state([
            'metric' => $name,
            'value' => $value ?? fake()->randomFloat(2, 0, 5),
            'source' => $source,
        ]);
    }
}
