<?php

namespace App\Infrastructure;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * Custom implementation of Symfony\Component\Messenger\HandleTrait for command/query buses.
 */
trait HandleTrait
{
    private readonly MessageBusInterface $commandBus;
    private readonly MessageBusInterface $queryBus;

    private function handleCommand(object $message): mixed
    {
        if (!isset($this->commandBus)) {
            throw new LogicException(sprintf('You must provide a "%s" instance in the "%s::$queryBus" property, but that property has not been initialized yet.', MessageBusInterface::class, static::class));
        }

        $envelope = $this->commandBus->dispatch($message);

        return $this->getResultFromEnvelope($envelope);
    }

    private function handleQuery(object $message): mixed
    {
        if (!isset($this->queryBus)) {
            throw new LogicException(sprintf('You must provide a "%s" instance in the "%s::$queryBus" property, but that property has not been initialized yet.', MessageBusInterface::class, static::class));
        }

        $envelope = $this->queryBus->dispatch($message);

        return $this->getResultFromEnvelope($envelope);
    }

    private function getResultFromEnvelope(Envelope $envelope): mixed
    {
        /** @var HandledStamp[] $handledStamps */
        $handledStamps = $envelope->all(HandledStamp::class);

        if (!$handledStamps) {
            throw new LogicException(sprintf('Message of type "%s" was handled zero times. Exactly one handler is expected when using "%s::%s()".', get_debug_type($envelope->getMessage()), static::class, __FUNCTION__));
        }

        if (\count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(function (HandledStamp $stamp): string {
                return sprintf('"%s"', $stamp->getHandlerName());
            }, $handledStamps));

            throw new LogicException(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected when using "%s::%s()", got %d: %s.', get_debug_type($envelope->getMessage()), static::class, __FUNCTION__, \count($handledStamps), $handlers));
        }

        return $handledStamps[0]->getResult();
    }
}
