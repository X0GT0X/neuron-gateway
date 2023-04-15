<?php

declare(strict_types=1);

namespace App\UserInterface\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class PayerRequestData
{
    public function __construct(
        #[Assert\NotBlank(message: 'Payer reference must be provided', allowNull: false)]
        public ?string $reference,
        public ?string $email,
        public ?string $name,
    ) {
    }
}
