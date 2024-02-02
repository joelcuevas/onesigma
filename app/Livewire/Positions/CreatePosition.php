<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use App\Models\Position;

class CreatePosition extends Component
{
    public Position $position;

    public $nextLevel;

    public function mount(Position $position)
    {
        $this->position = $position;

        $max = max($position->trackPositions->pluck('level')->all());
        $this->nextLevel = $max + 1;
    }

    public function save()
    {
        $max = max($this->position->trackPositions->pluck('level')->all());
        $nextLevel = $max + 1;
        $this->position->createLevel($nextLevel);

        $this->dispatch('close-modal', 'create-position');
        $this->dispatch('position-created');
    }
}
