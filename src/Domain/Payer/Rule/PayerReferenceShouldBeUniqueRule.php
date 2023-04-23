<?php

declare(strict_types=1);

namespace App\Domain\Payer\Rule;

use App\Domain\Payer\PayerCounterInterface;
use Neuron\BuildingBlocks\Domain\AbstractBusinessRule;

class PayerReferenceShouldBeUniqueRule extends AbstractBusinessRule
{
    public function __construct(
        private readonly string $reference,
        private readonly PayerCounterInterface $payerCounter
    ) {
    }

    public function isBroken(): bool
    {
        return $this->payerCounter->countByReference($this->reference) > 0;
    }

    public function getMessageTemplate(): string
    {
        return 'Payer with reference \'%s\' already exists';
    }

    public function getMessageArguments(): array
    {
        return [$this->reference];
    }
}
