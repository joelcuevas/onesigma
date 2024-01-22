<?php

namespace Database\Seeders;

use App\Models\Engineer;
use App\Models\Team;
use Illuminate\Database\Seeder;

class EngineersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tech = Team::find(1);
        $clusters = Team::factory(5)->hasSkillset()->create(['is_cluster' => true]);

        $clusters[0]->parent()->associate($tech)->save();
        $clusters[0]->children()->save($clusters[1]);
        $clusters[1]->children()->save($clusters[2]);

        $clusters[3]->parent()->associate($tech)->save();
        $clusters[3]->children()->save($clusters[4]);

        $branch2 = Team::factory(10)
            ->hasSkillset()
            ->has(Engineer::factory(5)->hasSkillset()->addMetrics())
            ->create();

        $branch4 = $branch2->splice(5);

        $branch2->each(function ($t) use ($clusters) {
            $t->parent()->associate($clusters[2])->save();
        });

        $branch4->each(function ($t) use ($clusters) {
            $t->parent()->associate($clusters[4])->save();
        });
    }
}
