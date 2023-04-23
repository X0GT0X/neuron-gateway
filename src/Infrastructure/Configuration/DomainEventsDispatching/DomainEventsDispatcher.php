<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\DomainEventsDispatching;

use App\Infrastructure\Configuration\Outbox\OutboxInterface;
use Neuron\BuildingBlocks\Application\Event\DomainEventNotificationInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationNotFoundException;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationsResolverInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsAccessorInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsDispatcherInterface;
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
