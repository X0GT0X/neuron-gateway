<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payment\Bank\BankId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Symfony\Component\Uid\Uuid;

class BankIdType extends GuidType
{
    public function getName(): string
    {
        return 'bank_id';
    }

    /**
     * @param BankId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): Uuid
    {
        return $value->getValue();
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): BankId
    {
        return new BankId(Uuid::fromString($value));
    }
}
