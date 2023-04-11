<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Domain;

use Symfony\Component\Uid\Uuid;

class DomainEventBase implements DomainEventInterface
{
    private Uuid $id;

    private \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
