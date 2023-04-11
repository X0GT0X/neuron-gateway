<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payment\PaymentStatus;

class PaymentStatusType extends AbstractEnumType
{
    public const NAME = 'payment_status';

    public static function getEnumClass(): string
    {
        return PaymentStatus::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
