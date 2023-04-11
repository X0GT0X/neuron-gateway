<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Application\Event;

#[\Attribute]
class DomainEventNotification
{
    public string $domainEvent;

    public function __construct(string $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
