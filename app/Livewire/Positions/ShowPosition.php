<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use App\Models\Position;

class ShowPosition extends Component
{
    public Position $position;

    public function render()
    {
        $positions = Position::query()
            ->where('group', $this->position->group)
            ->whereNot('type', 'group')
            ->get();

        return view('livewire.positions.show-position')->with([
            'positions' => $positions,
        ]);
    }
}
