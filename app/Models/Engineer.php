<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Enums\EngineerCareer;
use App\Models\Enums\EngineerDomain;
use App\Presenters\Engineer\HasCharts;
use App\Models\Traits\HasMetrics;
use App\Models\Traits\HasGrades;

class Engineer extends Model
{
    use HasFactory, HasCharts, HasMetrics, HasGrades;

    protected $fillable = [
        'name',
        'career',
        'career_level',
        'domain',
        'domain_level',
        'email',
        'is_internal',
        'is_guest',
        'velocity_id',
        'github_email',
    ];

    protected $casts = [
        'career' => EngineerCareer::class,
        'domain' => EngineerDomain::class,
        'is_internal' => 'boolean',
        'is_guest' => 'boolean',
    ];

    protected $with = [
        'latestMetrics',
    ];

    public function getInitialsAttribute()
    {
        $tokens = explode(' ', $this->name);
        $initials = ($tokens[0][0] ?? '').($tokens[1][0] ?? '');

        return trim(mb_strtoupper($initials));
    }

    public function getCareerNameAttribute()
    {;
        return __(mb_ucwords($this->career->name));
    }

    public function getDomainNameAttribute()
    {
        return __(mb_ucwords($this->domain->name));
    }

    public function getPositionAttribute()
    {
        return $this->careerName.' @ '.$this->domainName;
    }

    public static function scopeWithoutGuests(Builder $query)
    {
        $query->where('is_guest', 0);
    }

    public function teams(): MorphToMany
    {
        return $this->morphToMany(Team::class, 'teamable');
    }
}
