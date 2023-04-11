<?php

namespace App\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use App\BuildingBlocks\Infrastructure\UnitOfWorkInterface;

readonly class UnitOfWorkCommandHandlerDecorator implements CommandHandlerInterface
{
    public function __construct(
        private CommandHandlerInterface $inner,
        private UnitOfWorkInterface $unitOfWork,
    ) {
    }

    public function __invoke(CommandInterface $command): mixed
    {
        $result = $this->inner->__invoke($command);

        $this->unitOfWork->commit();

        return $result;
    }
}