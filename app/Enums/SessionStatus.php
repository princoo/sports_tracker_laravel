<?php

namespace App\Enums;

enum SessionStatus: string
{
    case SCHEDULED = 'SCHEDULED';
    case ACTIVE = 'ACTIVE';
    case COMPLETED = 'COMPLETED';
}