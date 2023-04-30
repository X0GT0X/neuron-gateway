<?php

declare(strict_types=1);

namespace App\Application\Payment\UpdatePayment;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Domain\Payer\PayerComposer;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\Exception\PaymentNotFoundException;
use App\Domain\Payment\PaymentId;
use App\Domain\Payment\PaymentRepositoryInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdatePaymentCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private PayerComposer $payerComposer,
    ) {
    }

    /**
     * @throws BusinessRuleValidationException
     * @throws PaymentNotFoundException
     */
    public function __invoke(UpdatePaymentCommand $command): void
    {
        $payer = null;

        if (null !== $command->payer && null !== $command->payer->reference) {
            $payer = $this->payerComposer->compose(
                $command->payer->reference,
                $command->payer->email,
                $command->payer->name,
            );
        }

        $payment = $this->paymentRepository->get(new PaymentId((string) $command->paymentId));

        $payment->update($payer?->id, $command->bankId ? new BankId((string) $command->bankId) : null);
    }
}
