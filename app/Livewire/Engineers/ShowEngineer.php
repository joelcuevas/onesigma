<?php

namespace App\Livewire\Engineers;

use Livewire\Component;
use App\Models\Engineer;

class ShowEngineer extends Component
{
    public Engineer $engineer;


    public function mount(Engineer $engineer) 
    {
        $this->engineer = $engineer;
    }

    public function render()
    {
        $grades = $this->engineer->careerGrades;

        $careerChartModel = 
            (new \Asantibanez\LivewireCharts\Models\RadarChartModel())
                ->addSeries('Skills', 'Technology', $grades->d1)
                ->addSeries('Skills', 'System', $grades->d2)
                ->addSeries('Skills', 'People', $grades->d3)
                ->addSeries('Skills', 'Process', $grades->d4)
                ->addSeries('Skills', 'Influence', $grades->d5);

        return view('livewire.engineers.show-engineer')
            ->with('careerChartModel', $careerChartModel)
            ->title($this->engineer->name.' : '.__('Ingeniero'));
    }
}
