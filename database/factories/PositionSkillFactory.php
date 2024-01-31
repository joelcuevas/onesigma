<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PositionSkill>
 */
class PositionSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $skill = 0;

        return [
            'track' => 'SE',
            'skill' => $skill++,
            'skill_label' => ucfirst(fake()->unique()->domainWord()),
            'l0_description' => fake()->paragraph(2),
            'l1_description' => fake()->paragraph(2),
            'l2_description' => fake()->paragraph(2),
            'l3_description' => fake()->paragraph(2),
            'l4_description' => fake()->paragraph(2),
            'l5_description' => fake()->paragraph(2),
        ];
    }
}
