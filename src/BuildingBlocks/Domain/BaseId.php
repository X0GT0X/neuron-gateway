<?php

namespace App\BuildingBlocks\Domain;

use Symfony\Component\Uid\Uuid;

class BaseId
{
    private Uuid $value;

    public function __construct(Uuid $value)
    {
        $this->value = $value;
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }

    public function equals(BaseId $other): bool
    {
        return $this->value->equals($other->value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
