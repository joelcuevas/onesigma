<?php

namespace App\Presenters\Engineer;

use Asantibanez\LivewireCharts\Models\RadarChartModel;

trait HasCharts
{
    public function getCareerChart()
    {
        $grade = $this->careerGrade;
        $chart = new RadarChartModel();

        $scores = $grade ? $grade->getScores() : [0, 0, 0, 0, 0];
        $dimensions = array_keys(config('onesigma.skills.dimensions.career'));

        foreach ($scores as $i => $score) {
            $chart->addSeries('', __(mb_convert_case($dimensions[$i], MB_CASE_TITLE)), $score);
        }

        return $chart;
    }
}