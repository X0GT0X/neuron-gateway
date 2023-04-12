<?php

declare(strict_types=1);

namespace App\Application\Payment\InitiatePayment;

use App\Application\Contract\AbstractCommand;
use App\Application\Payment\InitiatePayment\DTO\PayerDTO;
use App\Domain\Currency;
use App\Domain\Payment\PaymentType;
use Symfony\Component\Uid\Uuid;

class InitiatePaymentCommand extends AbstractCommand
{
    public function __construct(
        public readonly Currency $currency,
        public readonly int $amount,
        public readonly PaymentType $type,
        public readonly string $uniqueReference,
        public readonly PayerDTO $payer,
        public readonly ?Uuid $bankId,
    ) {
        parent::__construct();
    }
}
