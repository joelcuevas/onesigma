<?php

namespace App\Livewire\Teams;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Team;

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
