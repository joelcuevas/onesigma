<?php

namespace App\Models;

use App\Models\Traits\HasIdentities;
use App\Models\Traits\HasMetrics;
use App\Models\Traits\HasPosition;
use App\Models\Traits\HasSkillsets;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    use HasFactory, HasIdentities, HasMetrics, HasPosition, HasSkillsets;

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
