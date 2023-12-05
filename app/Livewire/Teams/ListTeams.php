<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team;

class ListTeams extends Component
{
    public $teams;

    public function mount()
    {
        $this->teams = Team::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.teams.list-teams')
            ->title(__('Equipos'));
    }
}
