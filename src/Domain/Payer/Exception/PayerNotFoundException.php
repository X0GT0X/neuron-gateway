<?php

declare(strict_types=1);

namespace App\Domain\Payer\Exception;

use App\Domain\Exception\EntityNotFoundException;

class PayerNotFoundException extends EntityNotFoundException
{
}
