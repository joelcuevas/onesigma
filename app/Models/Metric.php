<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'metric',
        'value',
        'latest',
        'context',
    ];

    protected $casts = [
        'period' => 'date',
        'latest' => 'boolean',
        'context' => 'array',
    ];

    public function __toString()
    {
        return $this->value;
    }
}
