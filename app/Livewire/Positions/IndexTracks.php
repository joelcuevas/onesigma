<?php

namespace App\Livewire\Positions;

use App\Models\Position;
use Livewire\Component;

class IndexTracks extends Component
{
    public function render()
    {
        $this->authorize('index', Position::class);

        return view('livewire.positions.index-tracks')->with([
            'tracks' => Position::where('type', 'track')->get(),
        ]);
    }
}
