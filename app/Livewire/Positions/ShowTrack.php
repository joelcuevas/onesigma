<?php

namespace App\Livewire\Positions;

use App\Models\Position;
use Livewire\Attributes\On;
use Livewire\Component;

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
