<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\BuildingBlocks\Infrastructure\DomainEventDispatching;

use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventNotificationNotFoundException;
use App\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventNotificationsResolver;
use PHPUnit\Framework\TestCase;

class DomainEventNotificationsResolverTest extends TestCase
{
    public function testThatReturnsNotificationTypeByDomainEvent(): void
    {
        $domainEventNotifications = [
            DomainEventNotificationStub::class,
        ];
        $domainEvent = new DomainEventStub();

        $notificationsResolver = new DomainEventNotificationsResolver($domainEventNotifications);

        $notificationType = $notificationsResolver->getNotificationTypeByDomainEvent($domainEvent);

        $this->assertEquals(DomainEventNotificationStub::class, $notificationType);
    }

    public function testThatThrowsExceptionWhenNotificationTypeNotFound(): void
    {
        $domainEvent = new DomainEventStub();

        $notificationsResolver = new DomainEventNotificationsResolver([]);

        $this->expectException(DomainEventNotificationNotFoundException::class);
        $this->expectExceptionMessage('Notification was not found for domain event App\Tests\UnitTest\BuildingBlocks\Infrastructure\DomainEventDispatching\DomainEventStub');

        $notificationsResolver->getNotificationTypeByDomainEvent($domainEvent);
    }
}
