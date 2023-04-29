<?php

declare(strict_types=1);

namespace App\Domain\Payment\Exception;

use App\Domain\Exception\EntityNotFoundException;

class PaymentNotFoundException extends EntityNotFoundException
{
}
