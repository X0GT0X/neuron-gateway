<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Infrastructure;

interface UnitOfWorkInterface
{
    public function commit(): void;
}
