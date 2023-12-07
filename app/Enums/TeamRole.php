<?php

namespace App\Enums;

enum TeamRole: string
{
    case Subteam = 'subteam';
    case Developer = 'developer';
    case Leader = 'leader';
    case Business = 'business';
    case Guest = 'guest';
}