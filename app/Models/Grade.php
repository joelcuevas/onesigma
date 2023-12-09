<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'track',
        'd1', 'd2', 'd3', 'd4', 'd5',
    ];

    public function getScores()
    {
        $dimensions = array_keys(config('onesigma.tracks.'.$this->track));
        $scores = [];

        foreach ($dimensions as $i => $dimension) {
            $label = __(mb_ucwords($dimension));
            $scores[$label] = $this['d'.$i+1] ?? 0;
        }

        return $scores;
    }
}
