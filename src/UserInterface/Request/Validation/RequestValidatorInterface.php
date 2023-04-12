<?php

declare(strict_types=1);

namespace App\UserInterface\Request\Validation;

use App\UserInterface\Request\RequestInterface;

interface RequestValidatorInterface
{
    /**
     * @throws RequestValidationException
     */
    public function validate(RequestInterface $request): void;
}
