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
    }

    public function index(User $user)
    {
        return $user->isManager();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function edit(User $user, Team $team)
    {
        return $user->isAdmin();
    }

    public function show(User $user, Team $team)
    {
        return $user->isManagerOfTeam($team);
    }

    public function editMembers(User $user, Team $team)
    {
        return $user->isManagerOfTeam($team);
    }
}
