<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Metric;

trait HasMetrics
{
    public function getMetric($metric)
    {
        $latest = $this->latestMetrics->where('metric', $metric)->first();    

        return $latest?->value ?? 0;
    }

    public function metrics(): MorphMany
    {
        return $this->morphMany(Metric::class, 'metricable');
    }

    public function latestMetrics(): MorphMany
    {
        return $this->morphMany(Metric::class, 'metricable')
            ->where('latest', true)
            ->orderBy('id', 'desc');
    }
}