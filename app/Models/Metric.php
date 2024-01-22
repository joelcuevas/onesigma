<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory;

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
        if ($this->target == 0) {
            if ($this->goal == 1) {
                return $this->value > 0 ? INF : -INF;
            } else {
                return $this->value > 0 ? -INF : INF;
            }
        }

        return (int) round($this->value * 100 / $this->target, 0) * $this->goal;
    }

    public function getDeviationAttribute()
    {
        if ($this->progress == 0) {
            return -100;
        }

        return round($this->progress - (100 * $this->goal), 0);
    }

    public function getStatusAttribute()
    {
        return match (true) {
            $this->deviation < -20 => 'danger',
            $this->deviation < 0 => 'warning',
            default => 'success',
        };
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
