<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payment\PaymentId;

class PaymentIdType extends AbstractIdType
{
    public const NAME = 'payment_id';

    public static function getIdClass(): string
    {
        return PaymentId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
