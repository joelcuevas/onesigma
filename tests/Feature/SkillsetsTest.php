<?php

namespace Tests\Feature;

use App\Models\Engineer;
use App\Models\Position;
use App\Models\Skillset;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\ConfigsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillsetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_engineers_have_skillsets(): void
    {
        $engineer = Engineer::factory()->hasSkillsets(1)->create();

        $this->assertEquals(1, $engineer->skillsets->count());
        $this->assertEquals($engineer->position_id, $engineer->skillset->position_id);
    }

    public function test_skillsets_are_scored_on_creation(): void
    {
        $se1 = Position::factory()->se1()->create();
        $se7 = Position::factory()->se7()->create();

        $engineer = Engineer::factory()
            ->recycle($se7)
            ->has(Skillset::factory()->se1())
            ->create();

        $skillset = $engineer->skillset;

        $this->assertEquals($se7->id, $skillset->position_id);
        $this->assertEquals(7, $skillset->level);
        $this->assertEquals(1, $skillset->score);
        $this->assertEquals(-6, $skillset->getScoreForGrader());
    }

    public function test_skill_charts_are_rendered_in_team_details()
    {
        $team = Team::factory()
            ->hasSkillset()
            ->has(Engineer::factory(5)->hasSkillset())
            ->create();

        $user = User::factory()->admin()->create();
        $user->teams()->attach($team);

        $this->actingAs($user);

        $this->get(route('teams.show', $team))
            ->assertSee('Capacidades')
            ->assertSee('Competencias')
            ->assertStatus(200);
    }

    public function test_skill_charts_are_rendered_in_engineer_profile()
    {
        $team = Team::factory()
            ->hasSkillset()
            ->has(Engineer::factory(5)->hasSkillset())
            ->create();

        $user = User::factory()->admin()->create();
        $user->teams()->attach($team);

        $this->actingAs($user);

        $this->get(route('engineers.show', $team->engineers->first()->id))
            ->assertSee('Capacidades')
            ->assertSee('Competencias')
            ->assertStatus(200);
    }
}
