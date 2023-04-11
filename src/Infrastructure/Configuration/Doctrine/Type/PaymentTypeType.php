<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payment\PaymentType;

class PaymentTypeType extends AbstractEnumType
{
    public const NAME = 'payment_type';

    public static function getEnumClass(): string
    {
        return PaymentType::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
