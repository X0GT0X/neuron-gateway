<?php

namespace App\Application\Payment\GetPayment;

readonly class PayerDTO
{
    public function __construct(
        public string $reference,
        public ?string $email,
        public ?string $name,
    ) {
    }
}
