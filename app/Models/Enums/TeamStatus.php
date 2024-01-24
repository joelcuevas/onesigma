<?php

namespace App\Models\Enums;

enum TeamStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
