<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Exception\PaymentNotFoundException;

interface PaymentRepositoryInterface
{
    public function add(Payment $payment): void;

    /** @throws PaymentNotFoundException */
    public function get(PaymentId $id): Payment;
}
