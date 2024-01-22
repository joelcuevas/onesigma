<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MetricConfig>
 */
class MetricConfigFactory extends Factory
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
            'label' => 'Commits',
            'target' => 3,
            'goal' => 1,
        ];
    }

    public function set($name, $target, $goal = 1)
    {
        return $this->state([
            'metric' => $name,
            'label' => $name,
            'target' => $target,
            'goal' => $goal,
        ]);
    }
}
