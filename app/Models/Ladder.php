<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ladder extends Model
{
    use HasFactory;

    protected $fillable = [
        'ladder',
        'technology',
        'system',
        'people',
        'process',
        'influence',
    ];
}
