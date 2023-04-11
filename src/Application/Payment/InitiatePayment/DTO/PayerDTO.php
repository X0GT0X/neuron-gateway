<?php

namespace App\Application\Payment\InitiatePayment\DTO;

readonly class PayerDTO
{
    public function __construct(
        public string  $reference,
        public ?string $email,
        public ?string $name,
    ) {}
}
