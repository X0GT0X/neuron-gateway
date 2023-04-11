<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment\DTO;

readonly class PayerDTO
{
    public function __construct(
        public string $reference,
        public ?string $email,
        public ?string $name,
    ) {
    }
}
