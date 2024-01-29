<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use App\Models\Position;
use Illuminate\Validation\Rule;

class ConfigPosition extends Component
{
    public Position $position;

    public $title;

    public $group;

    public function mount(Position $position)
    {
        $this->position = $position;

        $this->fill($position->only([
            'title', 'group',
        ]));
    }

    public function render()
    {
        abort_if(! $this->position->isGroup(), 404);

        return view('livewire.positions.config-position');
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => [
                'required', 
                'max:255',
                Rule::unique('positions')
                    ->where(fn ($q) => $q->where('type', 'group'))
                    ->ignore($this->position->id),
            ],
            'group' => [
                'required',
                'max:3',
                Rule::unique('positions')
                    ->where(fn ($q) => $q->where('type', 'group'))
                    ->ignore($this->position->id),
            ],
        ]);

        $this->position->fill($validated);
        $this->position->save();

        $this->redirect(route('positions.show', $this->position));
    }
}
