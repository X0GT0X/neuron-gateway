<?php

namespace App\BuildingBlocks\Application\Event;

use Attribute;

#[Attribute]
class DomainEventNotification {
    public string $domainEvent;

    public function __construct(string $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
