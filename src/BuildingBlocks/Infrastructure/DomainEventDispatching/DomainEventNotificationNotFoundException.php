<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Infrastructure\DomainEventDispatching;

use App\BuildingBlocks\Domain\DomainEventInterface;

class DomainEventNotificationNotFoundException extends \Exception
{
    public function __construct(DomainEventInterface $domainEvent)
    {
        parent::__construct(\sprintf('Notification was not found for domain event %s', \get_class($domainEvent)));
    }
}
