<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment\DTO;

use App\Domain\Currency;
use App\Domain\Payment\PaymentType;
use Symfony\Component\Uid\Uuid;

readonly class PaymentDTO
{
    public function __construct(
        public Currency $currency,
        public int $amount,
        public PaymentType $type,
        public string $uniqueReference,
        public PayerDTO $payer,
        public ?Uuid $bankId,
    ) {
    }
}
