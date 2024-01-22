<?php

namespace Tests\Feature;

use App\Models\Engineer;
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
        $this->assertEquals($engineer->track, $engineer->skillset->track);
    }

    public function test_skillsets_are_scored_on_creation(): void
    {
        $this->seed(ConfigsSeeder::class);

        $engineer = Engineer::factory()
            ->has(Skillset::factory()->se7())
            ->create();

        $skillset = $engineer->skillset;

        $this->assertEquals($skillset->track, $skillset->track);
        $this->assertEquals(7, $skillset->score);
    }

    public function test_skill_charts_are_rendered_in_team_details()
    {
        $this->seed(ConfigsSeeder::class);

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
        $this->seed(ConfigsSeeder::class);

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
