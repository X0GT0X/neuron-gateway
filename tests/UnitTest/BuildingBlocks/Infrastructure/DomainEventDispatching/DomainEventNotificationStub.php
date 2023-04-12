<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\BuildingBlocks\Infrastructure\DomainEventDispatching;

use App\BuildingBlocks\Application\Event\AbstractDomainEventNotification;
use App\BuildingBlocks\Application\Event\DomainEventNotification;

#[DomainEventNotification(DomainEventStub::class)]
readonly class DomainEventNotificationStub extends AbstractDomainEventNotification
{
}
