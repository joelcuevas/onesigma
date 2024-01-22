<?php

namespace App\Models\Pivots;

use App\Models\Enums\TeamEngineerRole;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamEngineer extends Pivot
{
    protected $casts = [
        'role' => TeamEngineerRole::class,
    ];
}
