<?php

declare(strict_types=1);

namespace App\Domain\Payment;

enum PaymentStatus: string
{
    case NEW_PAYMENT = 'NEW_PAYMENT';
}
