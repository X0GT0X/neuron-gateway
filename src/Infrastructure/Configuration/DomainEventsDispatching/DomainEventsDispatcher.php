<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\DomainEventsDispatching;

use App\BuildingBlocks\Application\Event\DomainEventNotificationInterface;
use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventNotificationNotFoundException;
use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventNotificationsResolverInterface;
use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventsAccessorInterface;
use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventsDispatcherInterface;
use App\Infrastructure\Configuration\Outbox\OutboxInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class DomainEventsDispatcher implements DomainEventsDispatcherInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
        //        private OutboxInterface $outbox,
        private DomainEventsAccessorInterface $domainEventsAccessor,
        private DomainEventNotificationsResolverInterface $notificationsResolver,
    ) {
    }

    public function dispatch(): void
    {
        $domainEvents = $this->domainEventsAccessor->getAllDomainEvents();

        /** @var DomainEventNotificationInterface[] $domainEventNotifications */
        $domainEventNotifications = [];

        foreach ($domainEvents as $domainEvent) {
            try {
                $domainEventNotification = $this->notificationsResolver->getNotificationTypeByDomainEvent($domainEvent);
                $domainEventNotifications[] = new $domainEventNotification($domainEvent->getId(), $domainEvent);
            } catch (DomainEventNotificationNotFoundException) {
                continue;
            }
        }

        $this->domainEventsAccessor->clearAllDomainEvents();

        foreach ($domainEvents as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }
    }
}
