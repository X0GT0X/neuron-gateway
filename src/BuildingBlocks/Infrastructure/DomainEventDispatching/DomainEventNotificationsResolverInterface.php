<?php

namespace App\BuildingBlocks\Infrastructure\DomainEventDispatching;

use App\BuildingBlocks\Domain\DomainEventInterface;

interface DomainEventNotificationsResolverInterface
{
    /**
     * @throws DomainEventNotificationNotFoundException
     */
    public function getNotificationTypeByDomainEvent(DomainEventInterface $domainEvent): string;
}
