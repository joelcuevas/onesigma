<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Engineer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ladder',
        'level',
        'email',
        'internal',
        'github_user',
        'velocity_id',
    ];

    protected $casts = [
        'internal' => 'bool',
    ];

    protected static function booted(): void
    {
        static::saving(function (Engineer $e) {
            $e->position = $e->ladder.$e->level;
        });
    }

    public function teams(): MorphToMany
    {
        return $this->morphToMany(Team::class, 'teamable');
    }

    public function ladders(): MorphMany
    {
        return $this->morphMany(Ladder::class, 'ladderable');
    }
}
