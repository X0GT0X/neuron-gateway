<?php

declare(strict_types=1);

namespace App\UserInterface\Request;

use App\Domain\Currency;
use App\Domain\Payment\PaymentType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

readonly class InitiatePaymentRequest implements RequestInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Currency must be provided', allowNull: false)]
        #[Assert\Choice(callback: [Currency::class, 'values'], message: 'Provided value {{ value }} is not one of the supported currencies. Try one of {{{ choices }}}')]
        public ?string $currency,
        #[Assert\NotBlank(message: 'Amount must be provided', allowNull: false)]
        public ?int $amount,
        #[Assert\NotBlank(message: 'Payment type must be provided', allowNull: false)]
        #[Assert\Choice(callback: [PaymentType::class, 'values'], message: 'Provided value {{ value }} is not one of the supported payment types. Try one of {{{ choices }}}')]
        public ?string $type,
        #[Assert\NotBlank(message: 'Unique reference must be provided', allowNull: false)]
        public ?string $uniqueReference,
        #[Assert\NotBlank(message: 'Payer data must be provided', allowNull: false)]
        #[Assert\Valid]
        public ?PayerRequestData $payer,
        #[Assert\Uuid(message: 'Bank ID should be a valid UUID')]
        public ?Uuid $bankId = null,
    ) {
    }
}
