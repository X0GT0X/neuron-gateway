<?php

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Currency;

class CurrencyType extends AbstractEnumType
{
    public const NAME = 'currency';

    public static function getEnumClass(): string
    {
        return Currency::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
