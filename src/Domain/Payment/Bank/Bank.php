<?php

declare(strict_types=1);

namespace App\Domain\Payment\Bank;

use App\BuildingBlocks\Domain\Entity;

class Bank extends Entity
{
    public BankId $id;

    private bool $isReadOnly;

    private function __construct(BankId $id, bool $isReadOnly)
    {
        $this->id = $id;
        $this->isReadOnly = $isReadOnly;
    }

    public static function create(BankId $id, bool $isReadOnly): self
    {
        return new self($id, $isReadOnly);
    }
}
