<?php

namespace App\Models\Pivots;

use App\Models\Enums\TeamUserRole;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    protected $casts = [
        'role' => TeamUserRole::class,
    ];
}
