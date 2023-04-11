<?php

namespace App\BuildingBlocks\Domain;

interface BusinessRuleInterface
{
    public function isBroken(): bool;

    public function getMessage(): string;
}
