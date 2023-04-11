<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration;

use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventsDispatcherInterface;
use App\BuildingBlocks\Infrastructure\UnitOfWorkInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UnitOfWork implements UnitOfWorkInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DomainEventsDispatcherInterface $domainEventsDispatcher,
    ) {
    }

    public function commit(): void
    {
        $this->domainEventsDispatcher->dispatch();

        $this->entityManager->flush();
    }
}
