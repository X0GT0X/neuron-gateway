<?php

declare(strict_types=1);

namespace App\Domain\Payer\Event;

use App\BuildingBlocks\Domain\DomainEventBase;
use App\Domain\Payer\PayerId;

class PayerCreatedDomainEvent extends DomainEventBase
{
    public function __construct(
        public readonly PayerId $payerId,
        public readonly string $reference,
        public readonly ?string $email,
        public readonly ?string $name,
    ) {
        parent::__construct();
    }
}
