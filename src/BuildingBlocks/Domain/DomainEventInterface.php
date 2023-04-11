<?php

namespace App\BuildingBlocks\Domain;

use Symfony\Component\Uid\Uuid;

interface DomainEventInterface
{
    public function getId(): Uuid;

    public function getOccurredOn(): \DateTimeImmutable;
}
