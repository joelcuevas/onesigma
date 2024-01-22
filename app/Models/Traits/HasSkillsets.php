<?php

namespace App\Models\Traits;

use App\Models\Skillset;

trait HasSkillsets
{
    public function skillset()
    {
        return $this->morphOne(Skillset::class, 'skillable')
            ->latestOfMany()
            ->withDefault();
    }

    public function skillsets()
    {
        return $this->morphMany(Skillset::class, 'skillable');
    }
}
