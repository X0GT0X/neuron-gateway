<?php

declare(strict_types=1);

namespace App\Tests\UnitTest;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Domain\Entity;

// TODO: move to neuron/building-blocks
class DomainEventsExtractor
{
    /**
     * @throws \ReflectionException
     *
     * @return DomainEventInterface[]
     */
    public static function getAllDomainEvents(Entity $aggregate): array
    {
        /** @var DomainEventInterface[] $domainEvents */
        $domainEvents = [];

        if ($aggregate->getDomainEvents() !== null) {
            \array_push($domainEvents, ...$aggregate->getDomainEvents());
        }

        $ref = new \ReflectionClass($aggregate);

        $properties = self::getAllClassProperties($ref);

        foreach ($properties as $property) {
            $isEntity = self::isEntity($property);

            if ($isEntity) {
                $entity = $property->getValue($aggregate);

                if ($entity instanceof Entity) {
                    \array_push($domainEvents, ...self::getAllDomainEvents($entity));
                }
            }

            if ($property->isInitialized($aggregate) && \is_iterable($iterable = $property->getValue($aggregate))) {
                foreach ($iterable as $value) {
                    if ($value instanceof Entity) {
                        \array_push($domainEvents, ...self::getAllDomainEvents($value));
                    }
                }
            }
        }

        return $domainEvents;
    }

    /** @throws \ReflectionException */
    private static function isEntity(\ReflectionProperty $property): bool
    {
        if ($property->getType()?->isBuiltin()) {
            return false;
        }

        $propertyRef = new \ReflectionClass($property->getType()?->getName());

        return $propertyRef->isSubclassOf(Entity::class);
    }

    /** @return \ReflectionProperty[] */
    private static function getAllClassProperties(\ReflectionClass $class): array
    {
        /** @var \ReflectionProperty[] $properties */
        $properties = [];

        \array_push($properties, ...$class->getProperties());

        if ($parent = $class->getParentClass()) {
            \array_push($properties, ...self::getAllClassProperties($parent));
        }

        return $properties;
    }
}
