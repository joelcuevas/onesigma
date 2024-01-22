<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Engineer;
use App\Livewire\Engineers\EditEngineer;
use Livewire\Livewire;
use Database\Seeders\ConfigsSeeder;

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
            ->set('track', 'SE7')
            ->call('update');

        $engineer->refresh();
        
        $this->assertEquals('newname', $engineer->name);
        $this->assertEquals('new@email.com', $engineer->email);
        $this->assertEquals('SE7', $engineer->track);
    }
}
