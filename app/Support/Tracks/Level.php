<?php

namespace App\Support\Tracks;

use Livewire\Wireable;

class Level implements Wireable
{
    public $track;

    public $dimension;

    public $name;

    public $label;

    public $description;

    public $help;

    public $score;

    function __construct($track, $dimension, $level)
    {
        $this->track = $track;
        $this->dimension = $dimension;
        $this->name = $level;

        $lang = "onesigma.{$track}.{$dimension}.{$level}";

        $this->label = __("{$lang}.label");
        $this->description = __("{$lang}.description");
        $this->help = markdown_view("{$track}.{$dimension}_{$level}");

        $config = config("onesigma.tracks.{$track}.{$dimension}");
        $this->score = array_search($level, $config) + 1;
    }

    public function toLivewire()
    {
        return [
            'track' => $this->track,
            'dimension' => $this->dimension,
            'level' => $this->name,
        ];
    }
 
    public static function fromLivewire($value)
    {
        $track = $value['track'];
        $dimension = $value['dimension'];
        $level = $value['level'];
 
        return new static($track, $dimension, $level);
    }
}
