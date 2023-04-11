<?php

declare(strict_types=1);

namespace App\Domain;

enum Currency: string
{
    case GBP = 'GBP';
    case EUR = 'EUR';
    case PLN = 'PLN';
}
