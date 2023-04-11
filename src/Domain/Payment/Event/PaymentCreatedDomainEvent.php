<?php

namespace App\Domain\Payment\Event;

use App\BuildingBlocks\Domain\DomainEventBase;
use App\Domain\Currency;
use App\Domain\Payer\PayerId;
use App\Domain\Payment\Bank\BankId;
use App\Domain\Payment\PaymentId;
use App\Domain\Payment\PaymentType;

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

    ) {
        parent::__construct();
    }
}
