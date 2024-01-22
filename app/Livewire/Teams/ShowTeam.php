<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowTeam extends Component
{
    public Team $team;

    public function render()
    {
        $this->authorize('show', $this->team);

        $this->team->load('engineers.position');
        $this->team->load('users');

        return view('livewire.teams.show-team');
    }

    #[On('team-updated')]
    public function refresh()
    {
        $this->team->refresh();
    }
}
