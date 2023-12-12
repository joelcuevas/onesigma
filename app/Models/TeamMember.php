<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use App\Models\Enums\TeamRole;

class TeamMember extends MorphPivot
{
    protected $casts = [
        'role' => TeamRole::class,
        'is_locked' => 'boolean',
    ];
}