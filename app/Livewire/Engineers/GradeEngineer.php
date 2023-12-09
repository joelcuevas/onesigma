<?php

namespace App\Livewire\Engineers;

use Livewire\Component;
use App\Models\Engineer;
use OneSigma;

class GradeEngineer extends Component
{
    public Engineer $engineer;

    public $step = 0;

    public $track = 'career';

    public $dimension;

    public $score = 1;

    public $scores = [];

    public function mount(Engineer $engineer)
    {
        $this->engineer = $engineer;
        $this->loadDimension();
    }

    public function grade() 
    {
        $validated = $this->validate([ 
            'score' => 'required|in:1,2,3,4,5',
        ]);

        $this->step++;

        $this->scores['d'.$this->step] = $validated['score'];
        $this->reset('score');
        $this->loadDimension();

        // save scores and reset modal
        if ($this->step > 4) {
            $this->scores['track'] = $this->track;
            $this->engineer->grades()->create($this->scores);
            $this->dispatch('engineer-updated');

            $this->resetModal();
        }
    }

    public function resetModal()
    {
        $this->dispatch('close-modal');
        $this->resetExcept('engineer');
        $this->loadDimension();
    }

    protected function loadDimension()
    {
        $track = OneSigma::track($this->track);
        $this->dimension = $track->dimension($this->step);
    }
}
