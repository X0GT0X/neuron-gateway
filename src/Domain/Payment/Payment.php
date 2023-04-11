<?php

namespace App\Domain\Payment;

use App\BuildingBlocks\Domain\AggregateRootInterface;
use App\BuildingBlocks\Domain\Entity;
use App\Domain\Currency;
use App\Domain\Payer\PayerId;
use App\Domain\Payment\Bank\Bank;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\Event\PaymentCreatedDomainEvent;
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

    private ?\DateTimeImmutable $updatedAt;

    public static function createNew(
        Currency $currency,
        int $amount,
        PaymentType $type,
        string $uniqueReference,
        PayerId $payerId,
        ?BankId $bankId
    ): self
    {
        return new self($currency, $amount, $type, $uniqueReference, $payerId, $bankId);
    }

    private function __construct(
        Currency $currency,
        int $amount,
        PaymentType $type,
        string $uniqueReference,
        PayerId $payerId,
        ?BankId $bankId
    ) {
        $this->id = new PaymentId(Uuid::v4());
        $this->currency = $currency;
        $this->amount = $amount;
        $this->type = $type;
        $this->uniqueReference = $uniqueReference;
        $this->payerId = $payerId;
        $this->status = PaymentStatus::NEW_PAYMENT;
        $this->bank = $bankId !== null ? Bank::create($bankId, true) : null;
        $this->createdAt = new \DateTimeImmutable();

        $this->addDomainEvent(new PaymentCreatedDomainEvent(
            $this->id,
            $this->currency,
            $this->amount,
            $this->type,
            $this->uniqueReference,
            $this->payerId,
            $this->bank?->id,
        ));
    }
}
