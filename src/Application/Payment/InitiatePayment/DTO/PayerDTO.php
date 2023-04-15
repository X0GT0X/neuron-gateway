<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class PayerDTO
{
    public function __construct(
        public string $reference,
        #[Assert\Email(message: 'Payer email must be a valid email address')]
        public ?string $email,
        public ?string $name,
    ) {
    }
}
