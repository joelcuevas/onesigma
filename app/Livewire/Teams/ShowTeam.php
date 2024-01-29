<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('team-updated')]
class ShowTeam extends Component
{
    public Team $team;

    public function render()
    {
        $this->authorize('show', $this->team);

        return view('livewire.teams.show-team');
    }
}
