<?php

namespace App\Models;

use App\Jobs\Graders\ScoreSkillset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skillset extends Model
{
    use HasFactory;

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
            $skillset->track = $skillset->skillable->track;

            $tokens = explode_track($skillset->track);
            $skillset->group = $tokens['group'];
            $skillset->level = $tokens['level'];

            $skillset->date = now();
        });

        static::created(function (Skillset $skillset) {
            ScoreSkillset::dispatchSync($skillset);
        });
    }

    public function getSkills($keyLabels = true)
    {
        $p = $this->position;

        return [
            $keyLabels ? $p->s0_label : 's0' => $this->s0,
            $keyLabels ? $p->s1_label : 's1' => $this->s1,
            $keyLabels ? $p->s2_label : 's2' => $this->s2,
            $keyLabels ? $p->s3_label : 's3' => $this->s3,
            $keyLabels ? $p->s4_label : 's4' => $this->s4,
            $keyLabels ? $p->s5_label : 's5' => $this->s5,
            $keyLabels ? $p->s6_label : 's6' => $this->s6,
            $keyLabels ? $p->s7_label : 's7' => $this->s7,
            $keyLabels ? $p->s8_label : 's8' => $this->s8,
            $keyLabels ? $p->s9_label : 's9' => $this->s9,
        ];
    }

    public function getExpectedSkills($keyLabels = true)
    {
        return $this->position->getExpectedSkills($keyLabels);
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

    public function position()
    {
        return $this->belongsTo(Position::class, 'track', 'track')->withDefault();
    }

    public function skillable()
    {
        return $this->morphTo();
    }
}
