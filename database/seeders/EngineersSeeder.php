<?php

namespace Database\Seeders;

use App\Models\Engineer;
use App\Models\Team;
use App\Models\Position;
use Illuminate\Database\Seeder;

class EngineersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $engineerPositions = Position::where('type', 'engineer')->get();
        $teamPositions = Position::where('type', 'team')->get();

        $tech = Team::find(1);
        
        $clusters = Team::factory(5)
            ->st3()
            ->cluster()
            ->hasSkillset()
            ->create();

        $clusters[0]->parent()->associate($tech)->save();
        $clusters[0]->children()->save($clusters[1]);
        $clusters[1]->children()->save($clusters[2]);

        $clusters[3]->parent()->associate($tech)->save();
        $clusters[3]->children()->save($clusters[4]);

        $engineers = Engineer::factory(5)
            ->recycle($engineerPositions)
            ->hasSkillset()
            ->addMetrics();

        $branch2 = Team::factory(10)
            ->st3() 
            ->hasSkillset()
            ->has($engineers)
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
