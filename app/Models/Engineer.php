<?php

namespace App\Models;

use App\Models\Traits\HasIdentities;
use App\Models\Traits\HasMetrics;
use App\Models\Traits\HasSkillsets;
use App\Models\Traits\HasPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    use HasPosition, HasFactory, HasIdentities, HasMetrics, HasSkillsets;

    protected $with = [
        'position',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_engineer')
            ->withPivot('role')
            ->as('member');
    }
}
