<?php

declare(strict_types=1);

namespace App\Domain\Payment\Event;

use App\Domain\Currency;
use App\Domain\Payer\PayerId;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\PaymentId;
use App\Domain\Payment\PaymentType;
use Neuron\BuildingBlocks\Domain\DomainEventBase;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

class PaymentCreatedDomainEvent extends DomainEventBase
{
    public function __construct(
        public readonly PaymentId $paymentId,
        public readonly Currency $currency,
        public readonly int $amount,
        public readonly PaymentType $paymentType,
        public readonly string $uniqueReference,
        public readonly PayerId $payerId,
        public readonly ?BankId $bankId,
        ?Uuid $id = null,
        ?\DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): DomainEventInterface
    {
        return new self(
            new PaymentId($data['paymentId']['value']),
            Currency::from($data['currency']),
            (int) $data['amount'],
            PaymentType::from($data['paymentType']),
            $data['uniqueReference'],
            new PayerId($data['payerId']['value']),
            $data['bankId'] ? new BankId($data['bankId']['value']) : null,
            $id,
            $occurredOn,
        );
    }
}
