<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IndexTeams extends Component
{
    public function render()
    {
        $this->authorize('index', Team::class);

        $teams = Auth::user()->getTeams();

        return view('livewire.teams.index-teams')->with([
            'teams' => $teams,
        ]);
    }
}
