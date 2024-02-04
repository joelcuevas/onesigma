<?php

namespace App\Models\Traits;

use App\Models\Metric;

trait HasMetrics
{
    public function getWatchedMetrics()
    {
        $watching = config('onesigma.metrics.watching');
        $watched = [];
        $latest = $this->getLatestMetrics();

        foreach ($watching as $w) {
            $watched[$w] = $latest[$w] ?? new Metric(['metric' => $w]);
        }

        return collect($watched);
    }

    public function getGradedMetrics()
    {
        $grading = $this->position
            ->getMetricConfigs()
            ->filter(fn ($m) => $m->is_gradeable);

        $graded = [];
        $latest = $this->getLatestMetrics();

        foreach ($grading as $g) {
            $m = $g->metric;

            if (isset($latest[$m])) {
                $graded[$m] = $latest[$m]->setTarget($g->target);
            } else {
                $graded[$m] = new Metric(['metric' => $m, 'target' => $g->target]);
            }
        }

        return $graded;
    }

    public function getLatestMetrics()
    {
        $class = strtolower(class_basename(static::class));

        $partition = function ($query) use ($class) {
            $query->from('metrics')
                ->selectRaw('*, ROW_NUMBER() OVER (PARTITION BY metric ORDER BY date DESC) AS rn')
                ->where('metricable_type', $class)
                ->where('metricable_id', $this->id);
        };

        return Metric::fromSub($partition, 'metrics')
            ->where('rn', 1)
            ->get()
            ->keyBy('metric');
    }

    public function metrics()
    {
        return $this->morphMany(Metric::class, 'metricable');
    }

    public function updateGrade($scores)
    {
        $score = null;
        $grade = '--';

        if (! is_null($scores)) {
            if (! is_array($scores)) {
                $scores = [$scores];
            }

            $score = (int) array_sum($scores);

            if ($score >= 0) {
                $grade = 'A+';
            } else {
                // -1 grade/letter for every -2 points
                $steps = max(-10, min(0, $score));
                $grade = chr(65 - ceil($steps / 2));
            }
        }

        $this->score = $score;
        $this->grade = $grade;
        $this->graded_at = now();
        $this->save();

        return $grade;
    }
}
