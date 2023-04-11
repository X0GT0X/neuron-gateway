<?php

namespace App\Infrastructure\Configuration\DomainEventsDispatching;

use App\BuildingBlocks\Domain\Entity;
use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventsAccessorInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DomainEventsAccessor implements DomainEventsAccessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getAllDomainEvents(): array
    {
        $domainEvents = [];

        $persistedEntities = $this->getPersistedEntities();

        foreach ($persistedEntities as $entity) {
            if ($entity instanceof Entity) {
                array_push($domainEvents, ...$entity->getDomainEvents());
            }
        }

        return $domainEvents;
    }

    public function clearAllDomainEvents(): void
    {
        $persistedEntities = $this->getPersistedEntities();

        foreach ($persistedEntities as $entity) {
            if ($entity instanceof Entity) {
                $entity->clearDomainEvents();
            }
        }
    }

    /** @return array<int, object> */
    private function getPersistedEntities(): array
    {
        $unitOfWork = $this->entityManager->getUnitOfWork();

        return [
            ...$unitOfWork->getScheduledEntityInsertions(),
            ...$unitOfWork->getScheduledEntityUpdates(),
            ...$unitOfWork->getScheduledEntityDeletions(),
        ];
    }
}
