<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Url;

class IndexTeams extends Component
{
    #[Url]
    public $inactive = false;

    public function render()
    {
        $this->authorize('index', Team::class);

        $teams = Auth::user()->getTeams($this->inactive);

        return view('livewire.teams.index-teams')->with([
            'teams' => $teams,
        ]);
    }
}
