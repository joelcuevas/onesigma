<?php

namespace App\Models\Traits;

use App\Models\Skillset;

trait HasSkillsets
{
    public function getCurrentSkills()
    {
        return $this->skillset->getCurrentSkills();
    }

    public function addSkillset(Skillset $skillset)
    {
        if ($this->skillset->equals($skillset)) {        
            return false;
        }

        $this->skillsets()->save($skillset);

        return true;
    }

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
