<?php

namespace App\Support\Tracks;

use Livewire\Wireable;

class Track implements Wireable
{
    public $track;

    public $dimensionNames;

    public $dimensions;

    public $levels;

    function __construct($track)
    {
        $this->track = $track;

        $config = config('onesigma.tracks.'.$track);
        $this->dimensionNames = array_keys($config);

        foreach ($this->dimensionNames as $d) {
            $this->dimensions[] = new Dimension($track, $d);
        }

        $this->dimensions = collect($this->dimensions);
    }

    public function dimension($dimension)
    {
        if (is_numeric($dimension)) {
            $slice = array_slice($this->dimensionNames, $dimension, 1, false);
            $dimension = $slice[0] ?? null;
        }

        return $dimension ? $this->dimensions->where('name', $dimension)->first() : null;
    }

    public function toLivewire()
    {
        return [
            'track' => $this->track,
        ];
    }
 
    public static function fromLivewire($value)
    {
        $track = $value['track'];
 
        return new static($track);
    }
}