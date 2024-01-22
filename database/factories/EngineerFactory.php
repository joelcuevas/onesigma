<?php

namespace Database\Factories;

use App\Models\Metric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EngineerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'track' => 'SE'.rand(1, 5),
        ];
    }

    public function addMetrics($count = 1, $metrics = null)
    {
        $self = $this;

        if ($metrics === null) {
            $metrics = config('onesigma.metrics.watching');
        }

        foreach ($metrics as $m) {
            $self = $self->has(Metric::factory()->count($count)->set($m));
        }

        return $self;
    }
}
