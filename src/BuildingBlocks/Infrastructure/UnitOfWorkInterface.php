<?php

namespace App\BuildingBlocks\Infrastructure;

interface UnitOfWorkInterface
{
    public function commit(): void;
}
