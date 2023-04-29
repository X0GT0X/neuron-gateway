<?php

declare(strict_types=1);

namespace App\Tests\UnitTest;

use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Domain\Entity;
use PHPUnit\Framework\TestCase;

// TODO: move to neuron/building-blocks
abstract class UnitTestCase extends TestCase
{
    /**
     * @template T
     *
     * @param class-string<T> $domainEventType
     *
     * @throws \ReflectionException
     *
     * @return array<T>
     */
    protected function assertPublishedDomainEvents(
        Entity $aggregate,
        string $domainEventType
    ): array {
        $domainEvents = \array_filter(
            DomainEventsExtractor::getAllDomainEvents($aggregate),
            static fn (DomainEventInterface $event) => $event instanceof $domainEventType
        );

        $this->assertContains(
            $domainEventType,
            \array_map(
                static fn (DomainEventInterface $domainEvent) => \get_class($domainEvent),
                $domainEvents
            ),
            \sprintf('%s event not published', $domainEventType)
        );

        return \array_values($domainEvents);
    }

    /**
     * @param class-string $businessRuleType
     */
    protected function expectBrokenRule(
        string $businessRuleType,
        string $expectedMessage,
        callable $testDelegate
    ): void {
        try {
            $testDelegate();
        } catch (BusinessRuleValidationException $exception) {
            $this->assertInstanceOf(
                expected: $businessRuleType,
                actual: $exception->getBrokenRule(),
                message: \sprintf('Expected %s broken rule', $businessRuleType),
            );

            $this->assertEquals($expectedMessage, $exception->getMessage());
        }
    }
}
