<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasMetrics;
use App\Models\Traits\HasGrades;
use App\Models\Enums\TeamRole;

class Team extends Model
{
    use HasFactory, HasMetrics, HasGrades;

    protected $fillable = [
        'name',
        'velocity_id',
        'is_root',
    ];

    protected $casts = [
        'is_root' => 'boolean',
    ];

    protected $with = [
        'nestedTeams',
        'latestMetrics',
    ];

    public static function getNestedTree()
    {
        $teams = static::orderBy('name')->withCount('members')->get();

        $flatTree = collect([]);

        $fn = function($team, $n = 0) use (&$fn, $flatTree) {
            $team->nestedLevel = $n;
            $flatTree->add($team);

            if ($team->nestedTeams) {
                foreach ($team->nestedTeams as $nt) {
                    $fn($nt, $n + 1);
                }
            }

            return $team;
        };

        $teams->where('is_root', true)->map(fn($t) => $fn($t));

        return $flatTree;
    }

    public function nestedTeams(): MorphToMany
    {
        return $this
            ->morphedByMany(Team::class, 'teamable')
            ->withCount('members')
            ->orderBy('name');
    }

    public function parentTeams(): MorphToMany
    {
        return $this
            ->morphToMany(Team::class, 'teamable')
            ->orderBy('name');
    }

    public function members(): MorphToMany 
    {
        return $this
            ->morphedByMany(Engineer::class, 'teamable')
            ->using(TeamMember::class)
            ->as('teamMember')
            ->withPivot('role', 'is_locked')
            ->orderBy('name');
    }

    public function getEngineersAttribute()
    {
        return $this->members
            ->whereIn('teamMember.role.value', [
                TeamRole::Engineer->value,
                TeamRole::Leader->value,
            ])
            ->sort(function ($a, $b) {
                if ($a->teamMember->role == $b->teamMember->role) {
                    return strcmp($a->name, $b->name);
                }

                return $a->teamMember->role == TeamRole::Leader ? -1 : 1;
            });
    }

    public function getManagersAttribute()
    {
        return $this->members
            ->whereIn('teamMember.role.value', [
                TeamRole::Manager->value,
            ]);
    }

    public function getGuestsAttribute()
    {
        return $this->members
            ->whereIn('teamMember.role.value', [
                TeamRole::Guest->value,
            ]);
    }
}
