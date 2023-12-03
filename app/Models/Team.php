<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'velocity_id',
    ];

    public function engineers(): MorphToMany 
    {
        return $this->morphedByMany(Engineer::class, 'teamable')->withPivot('role');
    }

    public function ladders(): MorphMany
    {
        return $this->morphMany(Ladder::class, 'ladderable');
    }

    public function nestedTeams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'teamable');
    }

    public function parentTeams(): MorphToMany
    {
        return $this->morphToMany(Team::class, 'teamable');
    }
}
