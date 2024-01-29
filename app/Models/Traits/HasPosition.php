<?php

namespace App\Models\Traits;

use App\Models\Position;

trait HasPosition
{
    public function getTitleAttribute()
    {
        return $this->position->title;
    }

    public function getTrackAttribute()
    {
        return $this->position->track;
    }

    public function position()
    {
        return $this->belongsTo(Position::class)->withDefault();
    }
}
