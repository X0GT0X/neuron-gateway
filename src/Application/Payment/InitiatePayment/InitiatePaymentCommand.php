<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment;

use App\Application\Contract\AbstractCommand;
use App\Application\Payment\InitiatePayment\DTO\PayerDTO;
use App\Domain\Currency;
use App\Domain\Payment\PaymentType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class InitiatePaymentCommand extends AbstractCommand
{
    public function __construct(
        public readonly Currency $currency,
        public readonly int $amount,
        public readonly PaymentType $type,
        #[Assert\Length(max: 16, maxMessage: 'Unique reference cannot be longer than {{ limit }} characters')]
        #[Assert\Regex('/^[A-Za-z0-9]+$/', message: 'Unique reference must be only alphanumeric characters')]
        public readonly string $uniqueReference,
        #[Assert\Valid]
        public readonly PayerDTO $payer,
        public readonly ?Uuid $bankId,
    ) {
        parent::__construct();
    }
}
