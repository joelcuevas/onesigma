<?php

namespace App\Models\Traits;

use App\Models\Position;

trait HasPosition
{
    public function getTitleAttribute()
    {
        return $this->position->title;
    }

    public function getLevelAttribute()
    {
        return $this->position->level;
    }

    public function getPositionSkills()
    {
        return $this->position->getExpectedSkills();
    }

    public function position()
    {
        return $this->belongsTo(Position::class)->withDefault();
    }
}
