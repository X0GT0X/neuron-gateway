<?php

declare(strict_types=1);

namespace App\Application\Contract;

interface GatewayModuleInterface
{
    public function executeCQuery(QueryInterface $query): mixed;

    public function executeCommand(CommandInterface $command): mixed;
}
