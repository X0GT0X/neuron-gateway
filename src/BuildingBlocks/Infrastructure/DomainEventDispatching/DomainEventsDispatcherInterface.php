<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Infrastructure\DomainEventDispatching;

interface DomainEventsDispatcherInterface
{
    public function dispatch(): void;
}
