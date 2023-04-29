<?php

declare(strict_types=1);

namespace App\Application\Payment\GetPayment;

use App\Application\Contract\QueryInterface;
use Symfony\Component\Uid\Uuid;

readonly class GetPaymentQuery implements QueryInterface
{
    public function __construct(
        public Uuid $paymentId,
    ) {
    }
}
