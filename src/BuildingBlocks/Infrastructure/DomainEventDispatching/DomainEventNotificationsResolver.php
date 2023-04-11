<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Infrastructure\DomainEventDispatching;

use App\BuildingBlocks\Application\Event\AbstractDomainEventNotification;
use App\BuildingBlocks\Application\Event\DomainEventNotification;
use App\BuildingBlocks\Domain\DomainEventInterface;

class DomainEventNotificationsResolver implements DomainEventNotificationsResolverInterface
{
    /** @var string[] */
    private array $domainEventNotifications = [];

    /**
     * @param string[] ...$domainEventNotifications
     */
    public function __construct(array ...$domainEventNotifications)
    {
        foreach ($domainEventNotifications as $notification) {
            \array_push($this->domainEventNotifications, ...$notification);
        }
    }

    public function getNotificationTypeByDomainEvent(DomainEventInterface $domainEvent): string
    {
        foreach ($this->domainEventNotifications as $notification) {
            $reflection = new \ReflectionClass($notification);

            if ($reflection->isSubclassOf(AbstractDomainEventNotification::class)) {
                $domainEventNotificationAttribute = $reflection->getAttributes(DomainEventNotification::class)[0];

                if ($domainEventNotificationAttribute->getArguments()[0] === $domainEvent::class) {
                    return $notification;
                }
            }
        }

        throw new DomainEventNotificationNotFoundException($domainEvent);
    }
}
