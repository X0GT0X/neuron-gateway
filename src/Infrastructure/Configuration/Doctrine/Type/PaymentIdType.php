<?php

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Payment\PaymentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Symfony\Component\Uid\Uuid;

class PaymentIdType extends GuidType
{
    public function getName(): string
    {
        return 'payment_id';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->getValue();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): PaymentId
    {
        return new PaymentId(Uuid::fromString($value));
    }
}
