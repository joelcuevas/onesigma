<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Engineer>
 */
class EngineerFactory extends Factory
{
    public function definition(): array
    {
        $ladders = ['D', 'TL', 'TPM', 'EM'];
        $ladder = array_rand($ladders);
        $level = rand(1, 7);

        return [
            'name' => fake()->name(),
            'ladder' => $ladder,
            'level' => $level,
        ];
    }
}
