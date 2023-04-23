<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payer\PayerId;

class PayerIdType extends AbstractIdType
{
    public const NAME = 'payer_id';

    public static function getIdClass(): string
    {
        return PayerId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
