<?php

namespace App\Models\Enums;

enum TeamRole: string
{
    case Engineer = 'engineer';
    case Leader = 'leader';
    case Manager = 'manager';
    case Guest = 'guest';
}