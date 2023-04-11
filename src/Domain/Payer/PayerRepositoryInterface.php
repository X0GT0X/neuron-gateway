<?php

declare(strict_types=1);

namespace App\Domain\Payer;

interface PayerRepositoryInterface
{
    public function add(Payer $payer): void;

    public function update(Payer $payer): void;
}
