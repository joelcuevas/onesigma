<?php

namespace Tests\Feature;

use App\Livewire\Positions\ConfigTrack;
use App\Livewire\Positions\IndexTracks;
use App\Livewire\Positions\ShowTrack;
use App\Models\Position;
use App\Models\PositionSkill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PositionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracks_can_be_listed()
    {
        $admin = User::factory()->admin()->create();
        $position = Position::factory()->track()->create();

        Livewire::actingAs($admin)
            ->test(IndexTracks::class)
            ->assertSee($position->title)
            ->assertOk();
    }

    public function test_tracks_details_can_be_shown()
    {
        $admin = User::factory()->admin()->create();

        $position = Position::factory()
            ->track()
            ->has(Position::factory(5), 'trackPositions')
            ->create();

        Livewire::actingAs($admin)
            ->test(ShowTrack::class, ['position' => $position])
            ->assertSee($position->trackPositions[0]->title)
            ->assertSee($position->trackPositions[1]->code)
            ->assertOk();
    }

    public function test_tracks_can_be_configured()
    {
        $admin = User::factory()->admin()->create();
        $position = Position::factory()->track()->create();
        PositionSkill::factory(10)->create();

        $skills = $position->skills->keyBy('skill');
        $labels = $levels = [];

        for ($i = 0; $i < 10; $i++) {
            $skill = $skills[$i] ?? [];
            $labels[$i] = $skill['skill_label'] ?? '';

            for ($j = 0; $j < 6; $j++) {
                $levels[$i][$j] = $skill["l{$j}_description"] ?? '';
            }
        }

        $labels[0] = 'NewSkill';
        $levels[1][2] = 'LoremIpsum';

        Livewire::actingAs($admin)
            ->test(ConfigTrack::class, ['position' => $position])
            ->set('labels', $labels)
            ->set('levels', $levels)
            ->call('save')
            ->assertHasNoErrors();

        $position->refresh();

        $this->assertEquals('NewSkill', $position->skills[0]->skill_label);
        $this->assertEquals('LoremIpsum', $position->skills[1]->l2_description);
    }
}
