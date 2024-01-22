<?php

function score_to_grade($score, $aplus)
{
    if ($score >= $aplus) {
        return 'A+';
    }

    $score = max(-8, min(0, $score));
    $score = ceil($score / 2);

    return chr(65 - $score);
}

function explode_track($track)
{
    $pos = strcspn($track, '0123456789');

    return [
        'group' => substr($track, 0, $pos),
        'level' => substr($track, $pos),
    ];
}
