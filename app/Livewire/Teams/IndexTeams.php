<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;

class IndexTeams extends Component
{
    #[Url]
    public $show = '';

    #[Session]
    public $expanded = ['root' => true];

    public function render()
    {
        $this->authorize('index', Team::class);

        $inactive = $this->show == 'inactive';
        $teams = Auth::user()->getTeams($inactive);

        return view('livewire.teams.index-teams')->with([
            'teams' => $teams,
        ]);
    }
}
