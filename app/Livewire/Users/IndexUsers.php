<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class IndexUsers extends Component
{
    public function render()
    {
        $this->authorize('index', User::class);

        return view('livewire.users.index-users')->with([
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
