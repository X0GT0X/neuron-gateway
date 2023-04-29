<?php

namespace App\Application\Payment\GetPayment;

final readonly class PaymentDTO
{
    public function __construct(
        public string $id,
        public string $currency,
        public int $amount,
        public string $type,
        public string $uniqueReference,
        public PayerDTO $payer,
        public ?string $bankId,
        public string $status,
        public string $createdAt,
        public bool $isBankReadOnly,
    ) {
    }
}
