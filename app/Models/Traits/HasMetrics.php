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
}
