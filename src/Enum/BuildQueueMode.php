<?php

declare(strict_types=1);

namespace App\Enum;

enum BuildQueueMode: string
{
    case Build = 'build';
    case Destroy = 'destroy';
}
