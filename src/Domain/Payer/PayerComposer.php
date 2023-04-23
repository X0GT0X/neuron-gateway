<?php

declare(strict_types=1);

namespace App\Domain\Payer;

use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;

final readonly class PayerComposer
{
    public function __construct(
        private PayerRepositoryInterface $payerRepository,
        private PayerCounterInterface $payerCounter
    ) {
    }

    /**
     * @throws BusinessRuleValidationException
     */
    public function compose(string $reference, ?string $email, ?string $name): Payer
    {
        $payer = $this->payerRepository->findByReference($reference);

        if (null === $payer) {
            $payer = Payer::createNew(
                $reference,
                $email,
                $name,
                $this->payerCounter,
            );

            $this->payerRepository->add($payer);
        } else {
            $payer->update($email, $name);
        }

        return $payer;
    }
}
