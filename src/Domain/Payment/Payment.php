<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Currency;
use App\Domain\Payer\PayerId;
use App\Domain\Payment\Bank\Bank;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\Event\PaymentCreatedDomainEvent;
use Neuron\BuildingBlocks\Domain\AggregateRootInterface;
use Neuron\BuildingBlocks\Domain\Entity;
use Symfony\Component\Uid\Uuid;

class Payment extends Entity implements AggregateRootInterface
{
    public PaymentId $id;

    private Currency $currency;

    private int $amount;

    private PaymentType $type;

    private string $uniqueReference;

    private PayerId $payerId;

    private PaymentStatus $status;

    private ?Bank $bank;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private function __construct(
        Currency $currency,
        int $amount,
        PaymentType $type,
        string $uniqueReference,
        PayerId $payerId,
        ?BankId $bankId
    ) {
        $this->id = new PaymentId((string) Uuid::v4());
        $this->currency = $currency;
        $this->amount = $amount;
        $this->type = $type;
        $this->uniqueReference = $uniqueReference;
        $this->payerId = $payerId;
        $this->status = PaymentStatus::NEW_PAYMENT;
        $this->bank = Bank::create($bankId, !(null === $bankId));
        $this->createdAt = new \DateTimeImmutable();

        $this->addDomainEvent(new PaymentCreatedDomainEvent(
            $this->id,
            $this->currency,
            $this->amount,
            $this->type,
            $this->uniqueReference,
            $this->payerId,
            $this->bank->id,
        ));
    }

    public static function createNew(
        Currency $currency,
        int $amount,
        PaymentType $type,
        string $uniqueReference,
        PayerId $payerId,
        ?BankId $bankId
    ): self {
        return new self($currency, $amount, $type, $uniqueReference, $payerId, $bankId);
    }
}
