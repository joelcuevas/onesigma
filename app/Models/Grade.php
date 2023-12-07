<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'track',
        'd1',
        'd2',
        'd3',
        'd4',
        'd5',
    ];

    public function getScores()
    {
        return [
            $this->d1,
            $this->d2,
            $this->d3,
            $this->d4,
            $this->d5,
        ];
    }
}
