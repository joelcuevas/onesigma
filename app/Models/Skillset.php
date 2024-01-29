<?php

namespace App\Models;

use App\Jobs\Graders\ScoreSkillset;
use App\Models\Traits\HasPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skillset extends Model
{
    use HasPosition, HasFactory;

    protected $casts = [
        'date' => 'date',
    ];

    protected $attributes = [
        's0' => 0, 's1' => 0, 's2' => 0, 's3' => 0, 's4' => 0,
        's5' => 0, 's6' => 0, 's7' => 0, 's8' => 0, 's9' => 0,
    ];

    protected static function booted()
    {
        static::creating(function (Skillset $skillset) {
            $skillset->position_id = $skillset->skillable->position_id;
            $skillset->date = now();
        });

        static::created(function (Skillset $skillset) {
            ScoreSkillset::dispatchSync($skillset);
        });
    }

    public function getLevelAttribute()
    {
        return $this->position->level;
    }

    public function getCurrentSkills()
    {
        $labels = $this->position->skills->pluck('skill_label', 'skill');

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

    public function getPositionSkills()
    {
        return $this->position->getExpectedSkills();
    }

    public function onlySkills()
    {
        return $this->only([
            's0', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9',
        ]);
    }

    public function equals(Skillset $compare)
    {
        for ($i = 0; $i < 10; $i++) {
            if ($this['s'.$i] != $compare['s'.$i]) {
                return false;
            }
        }

        return true;
    }

    public function getScoreForGrader()
    {
        $diff = (int) $this->score - $this->level;

        if ($diff < 0) {
            // -1 points for each level diff
            return $diff;
        }

        return 0;
    }

    public function skillable()
    {
        return $this->morphTo();
    }
}
