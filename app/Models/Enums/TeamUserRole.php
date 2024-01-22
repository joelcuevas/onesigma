<?php

namespace App\Models\Enums;

enum TeamUserRole: string
{
    case Manager = 'manager';
    case Support = 'support';
    case Business = 'business';
    case Guest = 'guest';
}
