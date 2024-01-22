<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
     
        return null;
    }

    public function index(User $user)
    {
        return $user->isManager();
    }

    public function show(User $user, Team $team)
    {
        return $user->isManagerOfTeam($team);
    }

    public function edit(User $user, Team $team)
    {
        return $user->isManagerOfTeam($team);
    }
}
