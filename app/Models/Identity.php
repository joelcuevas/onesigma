<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    use HasFactory;

    protected $casts = [
        'context' => 'array',
    ];

    public function identifiable()
    {
        return $this->morphTo();
    }
}
