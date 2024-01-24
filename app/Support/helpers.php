<?php

function explode_track($track)
{
    $pos = strcspn($track, '0123456789');

    return [
        'group' => substr($track, 0, $pos),
        'level' => substr($track, $pos),
    ];
}
