<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $attributes = [
        'type' => 'engineer',
        's0' => 0, 's1' => 0, 's2' => 0, 's3' => 0, 's4' => 0, 
        's5' => 0, 's6' => 0, 's7' => 0, 's8' => 0, 's9' => 0,
    ];

    public function isTrack()
    {
        return $this->type == 'track';
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

    public function skills()
    {
        return $this->hasMany(PositionSkill::class, 'track', 'track');
    }

    public function trackPositions()
    {
        return $this->hasMany(Position::class, 'parent_id', 'id');
    }
}
