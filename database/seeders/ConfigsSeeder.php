<?php

namespace Database\Seeders;

use App\Models\Enums\UserRole;
use App\Models\MetricConfig;
use App\Models\Position;
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
        MetricConfig::create([
            'metric' => 'commits_per_day',
            'label' => 'Commits per Day',
            'target' => 3,
            'goal' => 1,
            'unit' => 'Commits',
        ]);

        MetricConfig::create([
            'metric' => 'innovation_rate',
            'label' => 'Innovation Rate',
            'target' => 90,
            'goal' => 1,
            'unit' => '%',
        ]);

        MetricConfig::create([
            'metric' => 'time_to_review',
            'label' => 'Time to Review',
            'target' => 0,
            'goal' => -1,
            'unit' => 'Horas',
        ]);

        MetricConfig::create([
            'metric' => 'average_weekly_coding_days',
            'label' => 'Weekly Coding Days',
            'target' => 3.8,
            'goal' => 1,
            'unit' => 'Días',
        ]);

        MetricConfig::create([
            'metric' => 'rework_ratio',
            'label' => 'Rework',
            'target' => 8,
            'goal' => -1,
            'unit' => '%',
        ]);

        MetricConfig::create([
            'metric' => 'review_influence_ratio',
            'label' => 'Review Influence',
            'target' => 80,
            'goal' => 1,
            'unit' => '%',
        ]);

        $engineerSkills = [
            1 => [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
            2 => [1, 2, 2, 2, 1, 2, 2, 1, 1, 1],
            3 => [2, 2, 2, 3, 2, 3, 3, 2, 2, 2],
            4 => [3, 3, 3, 3, 2, 4, 4, 3, 3, 3],
            5 => [4, 4, 3, 4, 3, 5, 5, 4, 4, 4],
            6 => [5, 5, 3, 4, 4, 5, 5, 5, 4, 5],
            7 => [5, 5, 3, 4, 5, 5, 5, 5, 5, 5],
        ];

        foreach ($engineerSkills as $level => $scores) {
            Position::create([
                'type' => 'engineer',
                'track' => 'SE'.$level,
                'title' => 'Software Engineer '.$level,
                'level' => $level,
                's0_label' => 'Technology',
                's0' => $scores[0],
                's1_label' => 'System',
                's1' => $scores[1],
                's2_label' => 'People',
                's2' => $scores[2],
                's3_label' => 'Process',
                's3' => $scores[3],
                's4_label' => 'Influence',
                's4' => $scores[4],
                's5_label' => 'Coding',
                's5' => $scores[5],
                's6_label' => 'Databases',
                's6' => $scores[6],
                's7_label' => 'DevOps',
                's7' => $scores[7],
                's8_label' => 'Testing',
                's8' => $scores[8],
                's9_label' => 'SysDesign',
                's9' => $scores[9],
            ]);
        }

        $teamSkills = [
            1 => [2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
            2 => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3],
            3 => [4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
        ];

        foreach ($teamSkills as $level => $scores) {
            Position::create([
                'type' => 'team',
                'track' => 'ST'.$level,
                'title' => 'Software Team '.$level,
                'level' => $level,
                's0_label' => 'Technology',
                's0' => $scores[0],
                's1_label' => 'System',
                's1' => $scores[1],
                's2_label' => 'People',
                's2' => $scores[2],
                's3_label' => 'Process',
                's3' => $scores[3],
                's4_label' => 'Influence',
                's4' => $scores[4],
                's5_label' => 'Coding',
                's5' => $scores[5],
                's6_label' => 'Databases',
                's6' => $scores[6],
                's7_label' => 'DevOps',
                's7' => $scores[7],
                's8_label' => 'Testing',
                's8' => $scores[8],
                's9_label' => 'SysDesign',
                's9' => $scores[9],
            ]);
        }

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
