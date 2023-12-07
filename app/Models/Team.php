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
        'is_root',
    ];

    protected $casts = [
        'is_root' => 'boolean',
    ];

    public static function nestedTree()
    {
        $teams = static::orderBy('name')
            ->with('nestedTeams')
            ->withCount('engineers')
            ->get();

        $flat = collect([]);

        $fn = function($team, $n = 0) use (&$fn, $flat) {
            $team->nestedLevel = $n;
            $flat->add($team);

            if ($team->nestedTeams) {
                foreach ($team->nestedTeams as $nt) {
                    $fn($nt, $n + 1);
                }
            }

            return $team;
        };

        $teams->where('is_root', true)->map(fn($t) => $fn($t));

        return $flat;
    }

    public function engineers(): MorphToMany 
    {
        return $this
            ->morphedByMany(Engineer::class, 'teamable')
            ->withPivot('role')
            ->orderBy('name');
    }

    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradeable');
    }

    public function careerGrades(): MorphOne
    {
        return $this
            ->morphOne(Grade::class, 'gradeable')
            ->where('track', 'career')
            ->orderBy('id', 'desc');
    }

    public function nestedTeams(): MorphToMany
    {
        return $this
            ->morphedByMany(Team::class, 'teamable')
            ->with('nestedTeams')
            ->withCount('engineers')
            ->orderBy('name');
    }

    public function parentTeams(): MorphToMany
    {
        return $this
            ->morphToMany(Team::class, 'teamable')
            ->orderBy('name');
    }
}
