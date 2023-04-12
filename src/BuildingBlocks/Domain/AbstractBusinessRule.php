<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Domain;

abstract class AbstractBusinessRule implements BusinessRuleInterface
{
    abstract public function isBroken(): bool;

    abstract public function getMessageTemplate(): string;

    public function getMessage(): string
    {
        return \sprintf($this->getMessageTemplate(), ...$this->getMessageArguments());
    }

    public function getMessageArguments(): array
    {
        return [];
    }
}
