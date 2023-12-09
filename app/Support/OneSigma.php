<?php

namespace App\Support;

use App\Support\Tracks\Track;

class OneSigma
{
    public function track($track)
    {
        return new Track($track);
    }
}