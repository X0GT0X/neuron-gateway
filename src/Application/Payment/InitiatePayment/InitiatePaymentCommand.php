<?php

namespace App\Application\Payment\InitiatePayment;

use App\Application\Contract\AbstractCommand;
use App\Application\Payment\InitiatePayment\DTO\PaymentDTO;

class InitiatePaymentCommand extends AbstractCommand
{
    public function __construct(public readonly PaymentDTO $paymentDTO)
    {
        parent::__construct();
    }
}
