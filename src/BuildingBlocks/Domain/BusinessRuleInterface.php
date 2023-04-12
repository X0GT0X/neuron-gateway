<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Domain;

interface BusinessRuleInterface
{
    public function isBroken(): bool;

    public function getMessage(): string;

    public function getMessageTemplate(): string;

    /**
     * @return array<string|int|float>
     */
    public function getMessageArguments(): array;
}
