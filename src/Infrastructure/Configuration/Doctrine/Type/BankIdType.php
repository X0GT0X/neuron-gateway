<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payment\Bank\BankId;

class BankIdType extends AbstractIdType
{
    public const NAME = 'bank_id';

    public static function getIdClass(): string
    {
        return BankId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
