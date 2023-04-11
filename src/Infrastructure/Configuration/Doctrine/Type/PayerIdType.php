<?php

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payer\PayerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Symfony\Component\Uid\Uuid;

class PayerIdType extends GuidType
{
    public function getName(): string
    {
        return 'payer_id';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->getValue();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): PayerId
    {
        return new PayerId(Uuid::fromString($value));
    }
}
