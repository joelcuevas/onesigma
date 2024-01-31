<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use App\Models\Position;

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
