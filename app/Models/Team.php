<?php

namespace App\Models;

use App\Models\Pivots\TeamEngineer;
use App\Models\Pivots\TeamUser;
use App\Models\Traits\HasIdentities;
use App\Models\Traits\HasMetrics;
use App\Models\Traits\HasSkillsets;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Team extends Model
{
    use HasFactory, HasIdentities, HasMetrics, HasRecursiveRelationships, HasSkillsets;

    protected $casts = [
        'is_cluster' => 'boolean',
    ];

    protected $attributes = [
        'track' => 'ST3',
    ];

    public function isCluster()
    {
        return $this->is_cluster;
    }

    public static function getForUser(User $user)
    {
        $roots = function($query) use ($user) {
            $query->whereIn('id', $user->teams()->allRelatedIds());
        };

        return Team::treeOf($roots)
            ->depthFirst()
            ->get()
            ->unique(fn ($i) => $i->id)
            ->toTree()
            ->flatMap(function ($t) {
                return $t->descendantsAndSelf()->depthFirst()->get();
            });
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(TeamUser::class)
            ->withPivot('role')
            ->as('team')
            ->orderBy('name');
    }

    public function engineers()
    {
        return $this->belongsToMany(Engineer::class, 'team_engineer')
            ->using(TeamEngineer::class)
            ->withPivot('role')
            ->as('team')
            ->orderBy('name');
    }

    public function scopeWithoutClusters($query)
    {
        $query->where('is_cluster', false);
    }

    public function scopeOnlyClusters($query)
    {
        $query->where('is_cluster', true);
    }
}
