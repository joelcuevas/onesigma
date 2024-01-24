<?php

namespace App\Livewire\Users;

use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;

    public $name;

    public $email;

    public $password;

    public $role;

    public $allRoles;

    public function mount(User $user)
    {
        $this->user = $user;

        if ($this->user->exists) {
            $this->authorize('edit', $user);
        } else {
            $this->authorize('create', User::class);
        }

        $this->fill($user->only([
            'name', 'email', 'role',
        ]));

        $this->allRoles = UserRole::cases();
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'role' => [
                Rule::enum(UserRole::class),
            ],
            'password' => [
                'sometimes',
                'nullable',
                'max:255',
                'string',
                Password::defaults(),
            ],
        ]);

        if (! is_null($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $this->user->fill($validated);
        $this->user->save();

        $this->redirect(route('users'));
    }

    public function delete()
    {
        $this->user->delete();

        $this->redirect(route('users'));
    }
}
