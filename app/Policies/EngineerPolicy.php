<?php

namespace App\Policies;

use App\Models\Engineer;
use App\Models\User;

class EngineerPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function show(User $user, Engineer $engineer)
    {
        return $user->isManagerOfEngineer($engineer);
    }

    public function update(User $user, Engineer $engineer)
    {
        return $user->isManagerOfEngineer($engineer);
    }
}
