<?php

namespace App\Enums;

enum EngineerCareer: string
{
    case Engineer = 'E';
    case TechLead = 'TL';
    case EngineeringManager = 'EM';
    case TechProductManager = 'TPM';
    case ScrumMaster = 'SM';
}