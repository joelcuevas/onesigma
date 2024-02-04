<?php

namespace Database\Seeders;

use App\Models\Enums\UserRole;
use App\Models\MetricConfig;
use App\Models\Position;
use App\Models\PositionSkill;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed metric configs

        $metricConfigs = [
            [
                'metric' => 'commits_per_day',
                'label' => 'Commits per Day',
                'target' => 3,
                'goal' => 1,
                'unit' => 'Commits',
            ], [
                'metric' => 'innovation_rate',
                'label' => 'Innovation Rate',
                'target' => 90,
                'goal' => 1,
                'unit' => '%',
            ], [
                'metric' => 'time_to_review',
                'label' => 'Time to Review',
                'target' => 0,
                'goal' => -1,
                'unit' => 'Horas',
            ], [
                'metric' => 'average_weekly_coding_days',
                'label' => 'Weekly Coding Days',
                'target' => 3.8,
                'goal' => 1,
                'unit' => 'Días',
            ], [
                'metric' => 'rework_ratio',
                'label' => 'Rework',
                'target' => 8,
                'goal' => -1,
                'unit' => '%',
            ], [
                'metric' => 'review_influence_ratio',
                'label' => 'Review Influence',
                'target' => 80,
                'goal' => 1,
                'unit' => '%',
            ],
        ];

        $metrics = [];

        foreach ($metricConfigs as $config) {
            $metrics[] = MetricConfig::create($config);
        }

        $watchedMetrics = collect($metrics)
            ->whereIn('metric', [
                'average_weekly_coding_days',
                'rework_ratio',
                'review_influence_ratio',
                'time_to_review',
            ]);

        // seed software engineers

        $trackEngineer = new Position([
            'type' => 'engineer',
            'code' => 'SE',
            'track' => 'SE',
            'level' => 0,
            'title' => 'Software Engineer',
        ]);

        $trackEngineer->save();
        $trackEngineer->metrics()->sync($watchedMetrics->pluck('id')->all());

        $engineerSkills = [
            1 => [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
            2 => [1, 2, 2, 2, 1, 2, 1, 1, 1, 2],
            3 => [2, 2, 2, 3, 2, 3, 2, 2, 2, 3],
            4 => [3, 3, 3, 3, 2, 4, 3, 3, 3, 4],
            5 => [4, 4, 3, 4, 3, 5, 4, 4, 4, 5],
            6 => [5, 5, 3, 4, 4, 5, 5, 4, 5, 5],
            7 => [5, 5, 3, 4, 5, 5, 5, 5, 5, 5],
        ];

        foreach ($engineerSkills as $level => $scores) {
            Position::create([
                'type' => 'engineer',
                'parent_id' => $trackEngineer->id,
                'code' => 'SE'.$level,
                'track' => 'SE',
                'level' => $level,
                'title' => 'Software Engineer '.$level,
                's0' => $scores[0],
                's1' => $scores[1],
                's2' => $scores[2],
                's3' => $scores[3],
                's4' => $scores[4],
                's5' => $scores[5],
                's6' => $scores[6],
                's7' => $scores[7],
                's8' => $scores[8],
                's9' => $scores[9],
            ]);
        }

        // seed software teams

        $trackTeam = new Position([
            'type' => 'team',
            'code' => 'ST',
            'track' => 'ST',
            'level' => 0,
            'title' => 'Software Team',
        ]);

        $trackTeam->save();
        $trackTeam->metrics()->sync($watchedMetrics->pluck('id')->all());

        $teamSkills = [
            1 => [2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
            2 => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3],
            3 => [4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
        ];

        foreach ($teamSkills as $level => $scores) {
            Position::create([
                'type' => 'team',
                'parent_id' => $trackTeam->id,
                'code' => 'ST'.$level,
                'track' => 'ST',
                'level' => $level,
                'title' => 'Software Team '.$level,
                's0' => $scores[0],
                's1' => $scores[1],
                's2' => $scores[2],
                's3' => $scores[3],
                's4' => $scores[4],
                's5' => $scores[5],
                's6' => $scores[6],
                's7' => $scores[7],
                's8' => $scores[8],
                's9' => $scores[9],
            ]);
        }

        // seed position skills

        $skillLabels = [
            0 => 'Technology',
            1 => 'System',
            2 => 'People',
            3 => 'Process',
            4 => 'Influence',
            5 => 'Coding',
            6 => 'DevOps',
            7 => 'Testing',
            8 => 'SysDesign',
            9 => 'Culture',
        ];

        for ($i = 0; $i <= 9; $i++) {
            foreach (['SE', 'ST'] as $track) {
                PositionSkill::create([
                    'track' => $track,
                    'skill' => $i,
                    'skill_label' => $skillLabels[$i],
                    'l0_description' => fake()->paragraph(2),
                    'l1_description' => fake()->paragraph(2),
                    'l2_description' => fake()->paragraph(2),
                    'l3_description' => fake()->paragraph(2),
                    'l4_description' => fake()->paragraph(2),
                    'l5_description' => fake()->paragraph(2),
                ]);
            }
        }

        // seed base user

        $root = Team::create(['name' => 'Technology', 'is_cluster' => true]);

        $joel = User::factory()->create([
            'name' => 'Joel Cuevas',
            'email' => 'hola@joelcuevas.com',
            'role' => UserRole::Admin,
            'password' => null,
        ]);

        $joel->teams()->attach($root);
    }
}
