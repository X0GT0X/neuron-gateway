<?php

declare(strict_types=1);

namespace App\Domain;

use App\BuildingBlocks\Domain\StringEnumTrait;

enum Currency: string
{
    use StringEnumTrait;

    case GBP = 'GBP';
    case EUR = 'EUR';
    case PLN = 'PLN';
}
