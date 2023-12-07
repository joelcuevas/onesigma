<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team;

class ShowTeam extends Component
{
    public $team;
    
    public function mount(Team $team)
    {
        $this->team = $team;
    }
    
    public function render()
    {
        return view('livewire.teams.show-team')
            ->title($this->team->name.' : '.__('Equipo'));
    }
}
