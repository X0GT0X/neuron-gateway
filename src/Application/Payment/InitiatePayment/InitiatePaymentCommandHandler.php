<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment;

use App\Application\Configuration\CommandHandlerInterface;
use App\Domain\Payer\Payer;
use App\Domain\Payer\PayerRepositoryInterface;
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
        private PayerRepositoryInterface $payerRepository,
    ) {
    }

    public function __invoke(InitiatePaymentCommand $command): Uuid
    {
        $payer = Payer::createNew(
            $command->payer->reference,
            $command->payer->email,
            $command->payer->name
        );

        $this->payerRepository->add($payer);

        $payment = Payment::createNew(
            $command->currency,
            $command->amount,
            $command->type,
            $command->uniqueReference,
            $payer->id,
            $command->bankId ? new BankId($command->bankId) : null,
        );

        return $payment->id->getValue();
    }
}
