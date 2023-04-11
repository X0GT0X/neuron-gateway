<?php

namespace App\Domain\Payment;

interface PaymentRepositoryInterface
{
    public function add(Payment $payment): void;
}
