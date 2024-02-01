<?php

namespace App\Policies;

use App\Models\User;

class PositionPolicy
{
    public function index(User $user)
    {
        return $user->isAdmin();
    }

    public function show(User $user)
    {
        return $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function edit(User $user)
    {
        return $user->isAdmin();
    }
}
