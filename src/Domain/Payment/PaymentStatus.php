<?php

namespace App\Domain\Payment;

enum PaymentStatus : string
{
    case NEW_PAYMENT = 'NEW_PAYMENT';
}
