<?php

namespace App\Application\Payment\InitiatePayment;

use App\Application\Configuration\CommandHandlerInterface;
use App\Domain\Payer\Payer;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\Payment;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class InitiatePaymentCommandHandler implements CommandHandlerInterface
{
    public function __invoke(InitiatePaymentCommand $command): Uuid
    {
        $paymentDTO = $command->paymentDTO;

        $payer = Payer::createNew(
            $paymentDTO->payer->reference,
            $paymentDTO->payer->email,
            $paymentDTO->payer->name
        );

        $payment = Payment::createNew(
            $paymentDTO->currency,
            $paymentDTO->amount,
            $paymentDTO->type,
            $paymentDTO->uniqueReference,
            $payer->id,
            $paymentDTO->bankId ? new BankId($paymentDTO->bankId) : null,
        );

        return $payment->id->getValue();
    }
}
