<?php

namespace Database\Factories;

use App\Models\Metric;
use App\Models\Position;
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
            'position_id' => Position::factory(),
        ];
    }

    public function se1()
    {
        return $this->state(fn (array $attributes) => [
           'position_id' => Position::firstWhere('track', 'SE1')->id,
        ]);
    }

    public function se7()
    {
        return $this->state(fn (array $attributes) => [
           'position_id' => Position::firstWhere('track', 'SE7')->id,
        ]);
    }

    public function addMetrics($count = 1, $metrics = null)
    {
        $self = $this;

        if ($metrics === null) {
            $metrics = config('onesigma.metrics.velocity');
        }

        foreach ($metrics as $m) {
            $self = $self->has(Metric::factory()->count($count)->set($m));
        }

        return $self;
    }
}
