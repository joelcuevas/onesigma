<?php

namespace Tests\Feature;

use App\Livewire\Users\EditUser;
use App\Livewire\Users\IndexUsers;
use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

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
            ->assertForbidden();
    }
}
