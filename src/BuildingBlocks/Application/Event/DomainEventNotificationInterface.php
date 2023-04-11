<?php

namespace App\BuildingBlocks\Application\Event;

use App\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

interface DomainEventNotificationInterface
{
    public function getId(): Uuid;

    public function getDomainEvent(): DomainEventInterface;
}