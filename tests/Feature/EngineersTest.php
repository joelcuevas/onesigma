<?php

namespace Tests\Feature;

use App\Livewire\Engineers\EditEngineer;
use App\Livewire\Engineers\ScoreEngineer;
use App\Models\Team;
use App\Models\User;
use App\Models\Position;
use Database\Seeders\ConfigsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EngineersTest extends TestCase
{
    use RefreshDatabase;

    public function test_engineers_can_be_edited(): void
    {
        $this->seed(ConfigsSeeder::class);

        $manager = User::factory()->manager()->create();
        $team = Team::factory()->hasEngineers(1)->create();
        $manager->teams()->attach($team);
        $engineer = $team->engineers->first();

        Livewire::actingAs($manager)
            ->test(EditEngineer::class, ['engineer' => $engineer])
            ->assertOk()
            ->set('name', 'newname')
            ->set('email', 'new@email.com')
            ->set('position_id', Position::firstWhere('code', 'SE7')->id)
            ->call('update');

        $engineer->refresh();

        $this->assertEquals('newname', $engineer->name);
        $this->assertEquals('new@email.com', $engineer->email);
        $this->assertEquals('SE7', $engineer->position->code);
    }

    public function test_engineers_can_be_scored(): void
    {
        $manager = User::factory()->manager()->create();
        $team = Team::factory()->hasEngineers(1)->create();
        $manager->teams()->attach($team);
        $engineer = $team->engineers->first();

        Livewire::actingAs($manager)
            ->test(ScoreEngineer::class, ['engineer' => $engineer])
            ->assertOk()
            ->set('s0', 5)
            ->set('s1', 5)
            ->set('s2', 5)
            ->set('s3', 5)
            ->set('s4', 5)
            ->set('s5', 5)
            ->set('s6', 5)
            ->set('s7', 5)
            ->set('s8', 5)
            ->set('s9', 5)
            ->call('score');

        $engineer->refresh();
        $skills = array_values($engineer->skillset->getCurrentSkills());

        $this->assertEquals([5, 5, 5, 5, 5, 5, 5, 5, 5, 5], $skills);
    }
}
