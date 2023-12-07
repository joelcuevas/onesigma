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
        $this->setDimensionLevels();
    }

    public function grade() 
    {
        $this->step++;

        $this->scores['d'.$this->step] = $this->score;
        $this->reset('score');
        $this->setDimensionLevels();

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
        $this->setDimensionLevels();
    }

    protected function setDimensionLevels()
    {
        $dimensions = config('onesigma.skills.dimensions.'.$this->track);
        $values = array_slice($dimensions, $this->step, 1);
        $keys = array_keys($values);

        $this->dimension = array_shift($keys);
        $this->levels = array_shift($values);
    }
}
