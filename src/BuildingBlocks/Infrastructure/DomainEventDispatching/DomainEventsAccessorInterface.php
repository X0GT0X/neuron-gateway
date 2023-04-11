<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Infrastructure\DomainEventDispatching;

use App\BuildingBlocks\Domain\DomainEventInterface;

interface DomainEventsAccessorInterface
{
    /**
     * @return DomainEventInterface[]
     */
    public function getAllDomainEvents(): array;

    public function clearAllDomainEvents(): void;
}
