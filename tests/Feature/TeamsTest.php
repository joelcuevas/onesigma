<?php

namespace Tests\Feature;

use App\Models\Engineer;
use App\Models\Enums\TeamEngineerRole;
use App\Models\Enums\TeamUserRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Teams\EditMembers;

class TeamsTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_can_have_users_and_engineers()
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $engineer = Engineer::factory()->create();

        $user->teams()->attach($team, ['role' => TeamUserRole::Manager]);
        $engineer->teams()->attach($team, ['role' => TeamEngineerRole::Leader]);

        $this->assertEquals([$team->id], $user->teams->pluck('id')->all());
        $this->assertEquals([$team->id], $engineer->teams->pluck('id')->all());

        $this->assertEquals([$user->id], $team->users->pluck('id')->all());
        $this->assertEquals(TeamUserRole::Manager, $team->users->first()->team->role);

        $this->assertEquals([$engineer->id], $team->engineers->pluck('id')->all());
        $this->assertEquals(TeamEngineerRole::Leader, $team->engineers->first()->team->role);
    }

    public function test_managers_can_only_index_their_teams()
    {
        $user = User::factory()->manager()->create();

        $teams = Team::factory(2)->create();
        $user->teams()->attach($teams);

        $otherTeam = Team::factory()->create();

        $this->actingAs($user);

        $this->get(route('teams'))
            ->assertOk()
            ->assertSeeText($teams[0]->name)
            ->assertSeeText($teams[1]->name)
            ->assertDontSeeText($otherTeam->name);
    }

    public function test_admins_can_index_clustered_teams()
    {
        $user = User::factory()->admin()->create();

        $clusters = Team::factory(2)->create(['is_cluster' => true]);
        $clusters[1]->parent()->associate($clusters[0])->save();

        $teams = Team::factory(2)->create([
            'parent_id' => $clusters[1]->id,
        ]);

        $user->teams()->attach($clusters[0]);

        $this->assertEquals(4, $user->getTeams()->count());

        $forbidden = Team::factory()->create();

        $this->actingAs($user);

        $this->get(route('teams'))
            ->assertOk()
            ->assertSeeText($clusters[0]->name)
            ->assertSeeText($clusters[1]->name)
            ->assertSeeText($teams[0]->name)
            ->assertSeeText($teams[1]->name)
            ->assertDontSeeText($forbidden->name);
    }

    public function test_regular_users_can_not_index_teams()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('teams'))->assertStatus(403);
    }

    public function test_user_is_manager_of_team_and_engineer()
    {
        $team1 = Team::factory()->hasEngineers(1)->create();
        $team2 = Team::factory()->hasEngineers(1)->create();

        $user = User::factory()->manager()->create();
        $user->teams()->attach($team1);

        $this->assertTrue($user->isManagerOfTeam($team1));
        $this->assertFalse($user->isManagerOfTeam($team2));

        $this->assertTrue($user->isManagerOfEngineer($team1->engineers->first()));
        $this->assertFalse($user->isManagerOfEngineer($team2->engineers->first()));
    }

    public function test_team_members_can_be_edited()
    {
        $admin = User::factory()->admin()->create();
        $team = Team::factory()->hasEngineers(1)->create();
        $engineer = $team->engineers->first();

        $engineer->team->role = TeamEngineerRole::Engineer;
        $engineer->team->save();

        $this->assertDatabaseHas('team_engineer', [
            'team_id' => $team->id,
            'engineer_id' => $engineer->id,
            'role' => TeamEngineerRole::Engineer->value,
        ]);

        Livewire::actingAs($admin)
            ->test(EditMembers::class, [
                'team' => $team,
                'relationship' => 'engineers',
                'name' => 'edit-members',
            ])
            ->assertOk()
            ->set('memberRoles.'.$engineer->id, TeamEngineerRole::Leader->value)
            ->call('save');

        $this->assertDatabaseHas('team_engineer', [
            'team_id' => $team->id,
            'engineer_id' => $engineer->id,
            'role' => TeamEngineerRole::Leader->value,
        ]);
    }

    public function test_team_members_can_be_removed()
    {
        $admin = User::factory()->admin()->create();
        $team = Team::factory()->hasEngineers(5)->create();
        $engineer = $team->engineers->first();

        $this->assertEquals(5, $team->engineers()->count());

        $this->assertDatabaseHas('team_engineer', [
            'team_id' => $team->id,
            'engineer_id' => $engineer->id,
        ]);

        Livewire::actingAs($admin)
            ->test(EditMembers::class, [
                'team' => $team,
                'relationship' => 'engineers',
                'name' => 'edit-members',
            ])
            ->assertOk()
            ->call('remove', $engineer->id)
            ->call('save');

        $this->assertEquals(4, $team->fresh()->engineers()->count());

        $this->assertDatabaseMissing('team_engineer', [
            'team_id' => $team->id,
            'engineer_id' => $engineer->id,
        ]);
    }

    public function test_team_members_can_be_added()
    {
        $admin = User::factory()->admin()->create();
        $team = Team::factory()->hasEngineers(5)->create();
        $engineer = $team->engineers->first();

        $extraEngineer = Engineer::factory()->create();

        $this->assertEquals(5, $team->engineers()->count());

        $this->assertDatabaseMissing('team_engineer', [
            'team_id' => $team->id,
            'engineer_id' => $extraEngineer->id,
        ]);

        Livewire::actingAs($admin)
            ->test(EditMembers::class, [
                'team' => $team,
                'relationship' => 'engineers',
                'name' => 'edit-members',
            ])
            ->assertOk()
            ->set('orphanId', $extraEngineer->id)
            ->call('add')
            ->call('save');

        $this->assertEquals(6, $team->fresh()->engineers()->count());

        $this->assertDatabaseHas('team_engineer', [
            'team_id' => $team->id,
            'engineer_id' => $extraEngineer->id,
        ]);
    }
}
