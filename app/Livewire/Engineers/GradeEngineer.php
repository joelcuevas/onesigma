<?php

namespace App\Livewire\Engineers;

use Livewire\Component;
use App\Models\Engineer;

class GradeEngineer extends Component
{
    public Engineer $engineer;

    public $step = 0;

    public $track = 'career';

    public $dimension;

    public $levels = [];

    public $score;

    public $scores = [];

    public function mount(Engineer $engineer)
    {
        $this->engineer = $engineer;
    }

    public function render()
    {
        return view('livewire.engineers.grade-engineer');
    }

    public function grade() 
    {
        // prepare dimensions for next step
        if ($this->step < 5) {
            $dimensions = config('onesigma.skills.dimensions.'.$this->track);
            $values = array_slice($dimensions, $this->step, 1);
            $keys = array_keys($values);

            $this->dimension = array_shift($keys);
            $this->levels = array_shift($values);
        }

        // get score from prev step
        if ($this->step >=1 && $this->step <=5) {
            $this->scores['d'.$this->step] = $this->score;
            $this->reset('score');
        }

        $this->step++;  

        // save all scores in db and finish
        if ($this->step > 5) {
            $this->scores['track'] = $this->track;
            $this->engineer->grades()->create($this->scores);

            $this->resetModal();
        }
    }

    public function resetModal()
    {
        $this->dispatch('close-modal');
        $this->resetExcept('engineer');
    }
}
