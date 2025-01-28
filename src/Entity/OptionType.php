<?php

declare(strict_types=1);

namespace App\Entity;

enum OptionType: string
{
    case STRING = 'string';
    case INT = 'int';
    case FLOAT = 'float';
}
