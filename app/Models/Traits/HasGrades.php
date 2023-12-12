<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Metric;
use App\Models\Grade;

trait HasGrades
{
    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradeable');
    }

    public function careerGrades(): MorphOne
    {
        return $this->morphOne(Grade::class, 'gradeable')
            ->where('track', 'career')
            ->orderBy('id', 'desc');
    }
}