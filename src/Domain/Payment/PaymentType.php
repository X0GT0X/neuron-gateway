<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use Neuron\BuildingBlocks\Domain\StringEnumTrait;

enum PaymentType: string
{
    use StringEnumTrait;

    case OTHER = 'OTHER';
}
