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
        $level = rand(99, 99999999);

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

    public function se1()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'engineer',
                'track' => 'SE',
                'code' => 'SE1',
                'level' => 1,
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
                'type' => 'engineer',
                'track' => 'SE',
                'code' => 'SE7',
                'level' => 7,
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
