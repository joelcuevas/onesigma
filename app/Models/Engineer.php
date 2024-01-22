<?php

namespace App\Models;

use App\Models\Traits\HasIdentities;
use App\Models\Traits\HasMetrics;
use App\Models\Traits\HasSkillsets;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    use HasFactory, HasIdentities, HasMetrics, HasSkillsets;

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    protected $attributes = [
        'track' => 'SE1',
    ];

    public function getTitleAttribute()
    {
        return $this->position->title;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_engineer')
            ->withPivot('role')
            ->as('member');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'track', 'track')->withDefault();
    }
}
