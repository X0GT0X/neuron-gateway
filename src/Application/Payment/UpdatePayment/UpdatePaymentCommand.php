<?php

declare(strict_types=1);

namespace App\Application\Payment\UpdatePayment;

use App\Application\Contract\AbstractCommand;
use App\Application\Payment\UpdatePayment\DTO\PayerDTO;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePaymentCommand extends AbstractCommand
{
    public function __construct(
        public readonly Uuid $paymentId,
        #[Assert\Valid]
        public readonly ?PayerDTO $payer,
        public readonly ?Uuid $bankId,
    ) {
        parent::__construct();
    }
}
