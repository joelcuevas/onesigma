<?php

namespace App\Support\Tracks;

use Livewire\Wireable;

class Dimension implements Wireable
{
    public $track;

    public $name;

    public $label;

    public $levelNames;

    public $levels;

    function __construct($track, $dimension)
    {
        $this->track = $track;
        $this->name = $dimension;
        $this->label = __("onesigma.{$track}.{$dimension}.label");

        $this->levelNames = config("onesigma.tracks.{$track}.{$dimension}");

        foreach ($this->levelNames as $level) {
            $this->levels[] = new Level($track, $dimension, $level);
        }

        $this->levels = collect($this->levels);
    }

    public function level($level)
    {
        if (is_numeric($level)) {
            $slice = array_slice($this->levelNames, $level, 1, false);
            $level = $slice[0] ?? null;
        }

        return $level ? $this->levels->where('name', $level)->first() : null;
    }

    public function toLivewire()
    {
        return [
            'track' => $this->track,
            'dimension' => $this->name,
        ];
    }
 
    public static function fromLivewire($value)
    {
        $track = $value['track'];
        $dimension = $value['dimension'];
 
        return new static($track, $dimension);
    }
}
