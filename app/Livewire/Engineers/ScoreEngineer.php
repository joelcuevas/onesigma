<?php

namespace App\Livewire\Engineers;

use App\Metrics\Graders\Engineers\GradeEngineer;
use App\Models\Engineer;
use App\Models\Skillset;
use Livewire\Component;

class ScoreEngineer extends Component
{
    public Engineer $engineer;

    public $s0;

    public $s1;

    public $s2;

    public $s3;

    public $s4;

    public $s5;

    public $s6;

    public $s7;

    public $s8;

    public $s9;

    public function mount(Engineer $engineer)
    {
        $this->engineer = $engineer;

        $this->fill($engineer->skillset->onlySkills());
    }

    public function score()
    {
        $validated = $this->validate([
            's0' => ['required', 'numeric', 'min:0', 'max:5'],
            's1' => ['required', 'numeric', 'min:0', 'max:5'],
            's2' => ['required', 'numeric', 'min:0', 'max:5'],
            's3' => ['required', 'numeric', 'min:0', 'max:5'],
            's4' => ['required', 'numeric', 'min:0', 'max:5'],
            's5' => ['required', 'numeric', 'min:0', 'max:5'],
            's6' => ['required', 'numeric', 'min:0', 'max:5'],
            's7' => ['required', 'numeric', 'min:0', 'max:5'],
            's8' => ['required', 'numeric', 'min:0', 'max:5'],
            's9' => ['required', 'numeric', 'min:0', 'max:5'],
        ]);

        $skillset = new Skillset($validated);

        if ($this->engineer->addSkillset($skillset)) {
            GradeEngineer::dispatch($this->engineer);
        }

        $this->redirect(route('engineers.show', $this->engineer));
    }
}
