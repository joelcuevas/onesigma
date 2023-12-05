<?php

namespace App\Livewire\Engineers;

use Livewire\Component;
use App\Models\Engineer;

class ListEngineers extends Component
{
    public $engineers;

    public function mount()
    {
        $this->engineers = Engineer::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.engineers.list-engineers')
            ->title(__('Ingenieros'));
    }
}
