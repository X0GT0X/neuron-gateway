<?php

declare(strict_types=1);

namespace App\Domain\Payer\Event;

use App\Domain\Payer\PayerId;
use Neuron\BuildingBlocks\Domain\DomainEventBase;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

class PayerUpdatedDomainEvent extends DomainEventBase
{
    public function __construct(
        public readonly PayerId $payerId,
        public readonly string $reference,
        public readonly ?string $email,
        public readonly ?string $name,
        ?Uuid $id = null,
        ?\DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): DomainEventInterface
    {
        return new self(
            new PayerId($data['payerId']['value']),
            $data['reference'],
            $data['email'],
            $data['name'],
            $id,
            $occurredOn,
        );
    }
}
