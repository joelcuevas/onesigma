<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\EngineerCareer;
use App\Enums\EngineerDomain;

class Engineer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'career',
        'career_level',
        'domain',
        'domain_level',
        'email',
        'internal',
        'github_user',
        'velocity_id',
    ];

    protected $casts = [
        'career' => EngineerCareer::class,
        'domain' => EngineerDomain::class,
        'internal' => 'bool',
    ];

    public function getInitialsAttribute()
    {
        $tokens = explode(' ', $this->name);
        $initials = ($tokens[0][0] ?? '').($tokens[1][0] ?? '');

        return trim(mb_strtoupper($initials));
    }

    public function teams(): MorphToMany
    {
        return $this->morphToMany(Team::class, 'teamable');
    }

    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradeable');
    }

    public function careerGrades(): MorphOne
    {
        return $this->morphOne(Grade::class, 'gradeable')
            ->where('track', 'career')
            ->orderBy('id', 'desc');
    }
}
