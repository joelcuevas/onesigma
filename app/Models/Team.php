<?php

namespace App\Models;

use App\Models\Enums\TeamStatus;
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
        'status' => TeamStatus::class,
    ];

    protected $attributes = [
        'track' => 'ST3',
        'is_cluster' => false,
        'status' => TeamStatus::Active,
    ];

    public function isCluster()
    {
        return $this->is_cluster;
    }

    public static function getForUser(User $user, $includeInactive = false)
    {
        $query = Team::treeOf(function ($query) use ($user) {
            $query->whereIn('id', $user->teams()->allRelatedIds());
        });

        if (! $includeInactive) {
            $query->active();
        }

        // build paths and depths to sort items
        $tree = $query
            ->with(['engineers', 'children', 'ancestorsAndSelf'])
            ->get()
            ->map(function ($t) {
                $path = explode('.', $t->path);
                $t->depth = count($path) - 1;

                if ($t->children->count() == 0) {
                    $path[count($path) - 1] = 0;
                    $t->path = implode('.', $path);
                }

                foreach ($t->ancestorsAndSelf as $a) {
                    $t->rfqn = '#'.$a->name.'#'.$a->id.$t->rfqn;
                }

                return $t;
            })
            ->sortBy(['rfqn'])
            ->values();

        // remove duplicated branches
        $duplicates = [];

        for ($i = 0; $i < $tree->count(); $i++) {
            $ti = $tree[$i];

            for ($j = 0; $j < $tree->count(); $j++) {
                if ($i == $j) {
                    continue;
                }

                $tj = $tree[$j];

                if ($ti->id == $tj->id) {
                    $dup = $ti->depth > $tj->depth ? $j : $i;
                    $duplicates[] = $dup;
                }
            }
        }

        return $tree->reject(fn ($t, $i) => in_array($i, $duplicates));
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

    public function scopeActive($query)
    {
        $query->where('status', TeamStatus::Active);
    }
}
