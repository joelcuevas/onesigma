<?php

namespace App\Livewire\Users;

use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;

    public $name;

    public $email;

    public $role;

    public $allRoles;

    public function mount(User $user)
    {
        $this->user = $user;

        $this->fill($user->only([
            'name', 'email', 'role',
        ]));

        $this->allRoles = UserRole::cases();
        $this->role = UserRole::Admin;
    }

    public function save()
    {
        if ($this->user->exists) {
            $this->authorize('edit', $this->user);
        } else {
            $this->authorize('create', User::class);
        }

        $validated = $this->validate([
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'role' => [
                Rule::enum(UserRole::class),
            ],
        ]);

        $this->user->update($validated);

        $this->redirect(route('users'));
    }

    public function delete()
    {
        $this->authorize('delete', $this->user);

        $this->user->delete();

        $this->redirect(route('users'));
    }
}
