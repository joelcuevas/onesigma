<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\EngineerCareer;
use App\Enums\EngineerDomain;
use App\Presenters\Engineer\HasCharts;

class Engineer extends Model
{
    use HasFactory, HasCharts;

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

    public function getInitialsAttribute()
    {
        $tokens = explode(' ', $this->name);
        $initials = ($tokens[0][0] ?? '').($tokens[1][0] ?? '');

        return trim(mb_strtoupper($initials));
    }

    public function getCareerNameAttribute()
    {;
        return __(mb_convert_case($this->career->name, MB_CASE_TITLE));
    }

    public function getDomainNameAttribute()
    {
        return __(mb_convert_case($this->domain->name, MB_CASE_TITLE));
    }

    public static function scopeWithoutGuests(Builder $query)
    {
        $query->where('is_guest', 0);
    }

    public function teams(): MorphToMany
    {
        return $this->morphToMany(Team::class, 'teamable');
    }

    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradeable');
    }

    public function careerGrade(): MorphOne
    {
        return $this->morphOne(Grade::class, 'gradeable')
            ->where('track', 'career')
            ->orderBy('id', 'desc');
    }
}
