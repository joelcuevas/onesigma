<?php

namespace App\Models\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Engineer = 'engineer';
    case Guest = 'guest';
}
