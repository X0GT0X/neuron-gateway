<?php

namespace App\Domain\Payer;

interface PayerRepositoryInterface
{
    public function add(Payer $payer): void;

    public function update(Payer $payer): void;
}
