<?php

namespace App\Enums;

enum TeamRole: string
{
    case Engineer = 'engineer';
    case Leader = 'leader';
    case Guest = 'guest';
}