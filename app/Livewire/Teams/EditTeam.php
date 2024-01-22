<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team;

class EditTeam extends Component
{
    public Team $team;

    public $name;

    public $parent_id;

    public function mount(Team $team)
    {
        $this->team = $team;

        $this->fill($team->only([
            'name', 'parent_id',
        ]));
    }
}
