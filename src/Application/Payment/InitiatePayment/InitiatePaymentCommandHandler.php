<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\BuildingBlocks\Domain\BusinessRuleValidationException;
use App\Domain\Payer\PayerComposer;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final readonly class InitiatePaymentCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private PayerComposer $payerComposer,
    ) {
    }

    /**
     * @throws BusinessRuleValidationException
     */
    public function __invoke(InitiatePaymentCommand $command): Uuid
    {
        $payer = $this->payerComposer->compose(
            $command->payer->reference,
            $command->payer->email,
            $command->payer->name,
        );

        $payment = Payment::createNew(
            $command->currency,
            $command->amount,
            $command->type,
            $command->uniqueReference,
            $payer->id,
            $command->bankId ? new BankId($command->bankId) : null,
        );

        $this->paymentRepository->add($payment);

        return $payment->id->getValue();
    }
}
