<?php

namespace App\BuildingBlocks\Infrastructure\DomainEventDispatching;

interface DomainEventsDispatcherInterface
{
    public function dispatch(): void;
}
