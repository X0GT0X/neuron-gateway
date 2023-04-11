<?php

declare(strict_types=1);

namespace App\Domain\Payment;

interface PaymentRepositoryInterface
{
    public function add(Payment $payment): void;
}
