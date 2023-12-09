<?php

namespace App\Livewire\Engineers;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On; 
use Livewire\Component;
use App\Models\Engineer;

class ShowEngineer extends Component
{
    public Engineer $engineer;

    public function mount(Engineer $engineer) 
    {
        $this->engineer = $engineer;
    }

    #[Computed, On('engineer-updated')]
    public function careerChart()
    {
        return $this->engineer->fresh()->getCareerChart();
    }

    public function render()
    {
        return view('livewire.engineers.show-engineer')
            ->title($this->engineer->name.' : '.__('Ingeniero'));
    }
}
