<?php

namespace App\Presenters\Engineer;

use Asantibanez\LivewireCharts\Models\RadarChartModel;

trait HasCharts
{
    public function getCareerChart()
    {
        $chart = (new RadarChartModel())
            ->setJsonConfig([
                'yaxis.min' => 0,
                'yaxis.max' => 5,
                'yaxis.tickAmount' => 5,
            ]);

        $scores = $this->careerGrades?->getScores() ?? [0, 0, 0, 0, 0];

        foreach ($scores as $dimension => $score) {
            $chart->addSeries('', $dimension, $score);
        }

        return $chart;
    }
}