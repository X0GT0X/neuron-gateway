<?php

declare(strict_types=1);

namespace App\Domain\Payment\Rule;

use Neuron\BuildingBlocks\Domain\AbstractBusinessRule;

class BankCannotBeSetWhenIsReadOnlyRule extends AbstractBusinessRule
{
    public function __construct(
        private readonly ?bool $isBankReadOnly
    ) {
    }

    public function isBroken(): bool
    {
        return true === $this->isBankReadOnly;
    }

    public function getMessageTemplate(): string
    {
        return 'Bank cannot be set when is read-only';
    }
}
