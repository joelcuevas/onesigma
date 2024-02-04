<?php

namespace App\Models;

use App\Models\Enums\PositionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => PositionType::class,
    ];

    protected $attributes = [
        'type' => 'engineer',
        's0' => 0, 's1' => 0, 's2' => 0, 's3' => 0, 's4' => 0,
        's5' => 0, 's6' => 0, 's7' => 0, 's8' => 0, 's9' => 0,
    ];

    public function isTrack()
    {
        return is_null($this->parent_id);
    }

    public function createLevel(int $level)
    {
        if ($this->isTrack()) {
            $position = new Position([
                'type' => $this->type,
                'code' => $this->code.$level,
                'track' => $this->track,
                'level' => $level,
                'title' => $this->title.' '.$level,
            ]);

            $this->trackPositions()->save($position);
        }
    }

    public function getExpectedSkills()
    {
        $labels = $this->skills->pluck('skill_label', 'skill');

        return [
            $labels[0] ?? 0 => $this->s0,
            $labels[1] ?? 1 => $this->s1,
            $labels[2] ?? 2 => $this->s2,
            $labels[3] ?? 3 => $this->s3,
            $labels[4] ?? 4 => $this->s4,
            $labels[5] ?? 5 => $this->s5,
            $labels[6] ?? 6 => $this->s6,
            $labels[7] ?? 7 => $this->s7,
            $labels[8] ?? 8 => $this->s8,
            $labels[9] ?? 9 => $this->s9,
        ];
    }

    public function getSkillLabel($skill)
    {
        return $this->skills->firstWhere('skill', $skill)?->skill_label;
    }

    public function getMetricConfigs()
    {
        return $this->parentTrack
            ->metrics
            ->merge($this->metrics)
            ->map(function ($m) {
                $m->is_gradeable = (bool) $m->pivot->is_gradeable;
                $m->target = $m->pivot->target ?? $m->target;

                return $m;
            });
    }

    public function trackPositions()
    {
        return $this->hasMany(Position::class, 'parent_id', 'id');
    }

    public function parentTrack()
    {
        return $this->belongsTo(Position::class, 'parent_id', 'id');
    }

    public function skills()
    {
        return $this->hasMany(PositionSkill::class, 'track', 'track');
    }

    public function metrics()
    {
        return $this->belongsToMany(MetricConfig::class, 'position_metric')
            ->withPivot('target', 'is_gradeable');
    }

    public static function scopeTracks($query)
    {
        $query->whereNull('parent_id');
    }

    public static function scopeType($query, $type)
    {
        $query->where('type', $type)->whereNotNull('parent_id');
    }
}
