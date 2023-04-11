<?php

namespace App\UserInterface\Request;

readonly class Payer
{
    public function __construct(
        public string $reference,
        public ?string $email,
        public ?string $name,
    ) {
    }
}
