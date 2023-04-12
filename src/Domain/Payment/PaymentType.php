<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\BuildingBlocks\Domain\StringEnumTrait;

enum PaymentType: string
{
    use StringEnumTrait;

    case OTHER = 'OTHER';
}
