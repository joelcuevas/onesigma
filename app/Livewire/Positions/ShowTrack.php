<?php

namespace App\Livewire\Positions;

use App\Models\Position;
use Livewire\Component;
use Livewire\Attributes\On;

#[On('position-created')]
class ShowTrack extends Component
{
    public Position $position;

    public function render()
    {
        abort_unless($this->position->isTrack(), 404);

        $this->authorize('show', $this->position);

        return view('livewire.positions.show-track')->with([
            'positions' => $this->position->trackPositions,
        ]);
    }
}
