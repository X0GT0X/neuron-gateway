<?php

declare(strict_types=1);

namespace App\UserInterface\Request;

use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdatePaymentRequest implements RequestInterface
{
    public function __construct(
        #[Assert\Valid]
        public ?PayerRequestData $payer = null,
        #[Assert\Uuid(message: 'Bank ID should be a valid UUID')]
        public ?string $bankId = null,
    ) {
    }
}
