<?php

declare(strict_types=1);

namespace App\UserInterface\Request;

use Symfony\Component\Uid\Uuid;

readonly class InitiatePaymentRequest
{
    public function __construct(
        public string $currency,
        public int $amount,
        public string $type,
        public string $uniqueReference,
        public Payer $payer,
        public ?Uuid $bankId = null,
    ) {
    }
}
