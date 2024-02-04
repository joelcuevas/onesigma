<?php

namespace App\Models\Traits;

use App\Models\Metric;

trait HasMetrics
{
    public function getWatchedMetrics()
    {
        $watching = $this->position->getMetricConfigs();
        $latest = $this->getLatestMetrics();
        $watched = [];

        foreach ($watching as $w) {
            $name = $w->metric;

            if (isset($latest[$name])) {
                $watched[$name] = $latest[$name];
            } else {
                $watched[$name] = new Metric(['metric' => $name]);
            }

            $watched[$name]->setTarget($w->target);
            $watched[$name]->is_gradeable = $w->is_gradeable;
        }

        return collect($watched);
    }

    public function getGradedMetrics()
    {
        return $this->getWatchedMetrics()->filter(fn ($m) => $m->is_gradeable);
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
