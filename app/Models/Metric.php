<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory;

    const INCREASE = 1;

    const DECREASE = -1;

    protected $with = ['config'];

    protected $casts = [
        'date' => 'date',
        'context' => 'array',
    ];

    public function getLabelAttribute()
    {
        return $this->config->label;
    }

    public function getTargetAttribute()
    {
        return $this->config->target;
    }

    public function getGoalAttribute()
    {
        return $this->config->goal;
    }

    public function getUnitAttribute()
    {
        return $this->config->unit;
    }

    public function getProgressAttribute()
    {
        if ($this->value == null) {
            return null;
        }

        if ($this->goal == Metric::INCREASE) {
            if ($this->value >= $this->target) {
                return 100;
            }
        }

        if ($this->goal == Metric::DECREASE) {
            if ($this->value <= $this->target) {
                return 100;
            }
        }

        if ($this->target == 0) {
            return INF * $this->goal;
        }

        return (int) round($this->value * 100 / $this->target, 0);
    }

    public function getDeviationAttribute()
    {
        return abs(round(100 - $this->progress, 0));
    }

    public function getStatusAttribute()
    {
        return match (true) {
            $this->deviation == INF => 'danger',
            $this->deviation > 20 => 'danger',
            $this->deviation > 0 => 'warning',
            default => 'success',
        };
    }

    public function getScoreForGrader()
    {
        if ($this->deviation == INF) {
            // -1 point if INF deviation (i.e. target = 0)
            return -1;
        } elseif ($this->deviation > 0) {
            // -1 point for each 20% deviation
            return round(($this->deviation) / 20, 0) * -1;
        }

        return 0;
    }

    public function config()
    {
        return $this->belongsTo(MetricConfig::class, 'metric', 'metric')->withDefault();
    }

    public function metricable()
    {
        return $this->morphTo();
    }
}
