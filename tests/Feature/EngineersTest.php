<?php

namespace Tests\Feature;

use App\Livewire\Engineers\EditEngineer;
use App\Livewire\Engineers\ScoreEngineer;
use App\Metrics\Graders\Engineers\GradeEngineer;
use App\Models\Position;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class EngineersTest extends TestCase
{
    use RefreshDatabase;

    public function test_engineers_can_be_edited(): void
    {
        $manager = User::factory()->manager()->create();
        $team = Team::factory()->hasEngineers(1)->create();
        $manager->teams()->attach($team);
        $engineer = $team->engineers->first();
        $position = Position::factory()->create();

        $this->assertNotEquals($position->id, $engineer->position->id);

        Livewire::actingAs($manager)
            ->test(EditEngineer::class, ['engineer' => $engineer])
            ->assertOk()
            ->set('name', 'newname')
            ->set('email', 'new@email.com')
            ->set('position_id', $position->id)
            ->call('update');

        $engineer->refresh();

        $this->assertEquals('newname', $engineer->name);
        $this->assertEquals('new@email.com', $engineer->email);
        $this->assertEquals($position->code, $engineer->position->code);
    }

    public function test_engineers_can_be_scored(): void
    {
        $manager = User::factory()->manager()->create();
        $team = Team::factory()->hasEngineers(1)->create();
        $manager->teams()->attach($team);
        $engineer = $team->engineers->first();

        Queue::fake();

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

        Queue::assertPushed(GradeEngineer::class, 1);

        $engineer->refresh();
        $skills = array_values($engineer->skillset->getCurrentSkills());

        $this->assertEquals([5, 5, 5, 5, 5, 5, 5, 5, 5, 5], $skills);

        // re-submit same values to validate engineer is not re-scored
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

        Queue::assertPushed(GradeEngineer::class, 1);
    }
}
