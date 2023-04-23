<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Psr\Log\LoggerInterface;

readonly class LoggingCommandHandlerDecorator implements CommandHandlerInterface
{
    public function __construct(
        private CommandHandlerInterface $inner,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(CommandInterface $command): mixed
    {
        $this->logger->info(\sprintf('Executing command %s', \get_class($command)));

        try {
            $result = $this->inner->__invoke($command);

            $this->logger->info(\sprintf('Command %s processed successfully', \get_class($command)));

            return $result;
        } catch (\Throwable $exception) {
            if ($exception instanceof BusinessRuleValidationException) {
                $this->logger->error(\sprintf('Command %s processing failed', \get_class($command)), [
                    'exception' => $exception,
                ]);
            }

            $this->logger->critical(\sprintf('Command %s processing failed with unexpected error', \get_class($command)), [
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
