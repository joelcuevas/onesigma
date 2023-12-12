<?php

namespace App\Models\Enums;

enum EngineerCareer: string
{
    case Engineer = 'E';
    case Lead = 'TL';
    case Manager = 'EM';
    case TPM = 'TPM';
    case Scrum = 'SM';
}