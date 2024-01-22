<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $attributes = [
        'type' => 'engineer',
    ];

    protected static function booted()
    {
        static::creating(function (Position $position) {
            $tokens = explode_track($position->track);
            $position->group = $tokens['group'];
            $position->level = $tokens['level'];
        });
    }

    public function getExpectedSkills($keyLabels = true)
    {
        return [
            $keyLabels ? $this->s0_label : 's0' => $this->s0,
            $keyLabels ? $this->s1_label : 's1' => $this->s1,
            $keyLabels ? $this->s2_label : 's2' => $this->s2,
            $keyLabels ? $this->s3_label : 's3' => $this->s3,
            $keyLabels ? $this->s4_label : 's4' => $this->s4,
            $keyLabels ? $this->s5_label : 's5' => $this->s5,
            $keyLabels ? $this->s6_label : 's6' => $this->s6,
            $keyLabels ? $this->s7_label : 's7' => $this->s7,
            $keyLabels ? $this->s8_label : 's8' => $this->s8,
            $keyLabels ? $this->s9_label : 's9' => $this->s9,
        ];
    }
}
