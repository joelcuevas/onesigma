<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use App\Models\Position;

class IndexPositions extends Component
{
    public function render()
    {
        $this->authorize('index', Position::class);
        
        return view('livewire.positions.index-positions')->with([
            'positions' => Position::where('type', 'group')->get(),
        ]);
    }
}
