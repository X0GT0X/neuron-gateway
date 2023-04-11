<?php

declare(strict_types=1);

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

    /**
     * @param PayerId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): Uuid
    {
        return $value->getValue();
    }

    /**
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): PayerId
    {
        return new PayerId(Uuid::fromString($value));
    }
}
