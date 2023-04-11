<?php

declare(strict_types=1);

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

    /**
     * @param PaymentId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): Uuid
    {
        return $value->getValue();
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): PaymentId
    {
        return new PaymentId(Uuid::fromString($value));
    }
}
