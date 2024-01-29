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
}
