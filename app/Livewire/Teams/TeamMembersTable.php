<?php

namespace App\Livewire\Teams;

use Livewire\Component;

class TeamMembersTable extends Component
{
    public $team;

    public function mount($team = null)
    {
        $this->team = $team;
    }
}
