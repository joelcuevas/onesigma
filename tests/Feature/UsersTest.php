<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Users\EditUser;
use App\Livewire\Users\IndexUsers;
use App\Models\Enums\UserRole;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_index_users(): void
    {
        $admin = User::factory()->admin()->create();
        $users = User::factory(5)->create();

        $this->actingAs($admin);

        Livewire::actingAs($admin)
            ->test(IndexUsers::class)
            ->assertSee($users[0]->name)
            ->assertOk();

        $manager = User::factory()->manager()->create();

        Livewire::actingAs($manager)
            ->test(IndexUsers::class)
            ->assertForbidden();
    }

    public function test_only_admins_can_edit_users(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(EditUser::class, ['user' => $user])
            ->assertOk()
            ->set('name', fake()->name())
            ->set('email', fake()->email())
            ->set('role', UserRole::Manager->value)
            ->call('save');

        $manager = User::factory()->manager()->create();

        Livewire::actingAs($manager)
            ->test(EditUser::class, ['user' => $user])
            ->set('name', fake()->name())
            ->call('save')
            ->assertForbidden();
    }

    public function test_only_admins_can_create_users(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(EditUser::class)
            ->assertOk()
            ->set('name', fake()->name())
            ->set('email', fake()->email())
            ->set('role', UserRole::Admin->value)
            ->call('save');

        $manager = User::factory()->manager()->create();

        Livewire::actingAs($manager)
            ->test(EditUser::class)
            ->set('name', fake()->name())
            ->call('save')
            ->assertForbidden();
    }

    public function test_only_admins_can_delete_users(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(EditUser::class, ['user' => $user])
            ->assertOk()
            ->call('delete');

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);

        $manager = User::factory()->manager()->create();
        $user = User::factory()->create();

        Livewire::actingAs($manager)
            ->test(EditUser::class, ['user' => $user])
            ->assertOk()
            ->call('delete')
            ->assertForbidden();
    }
}
