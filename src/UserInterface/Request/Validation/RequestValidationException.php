<?php

declare(strict_types=1);

namespace App\UserInterface\Request\Validation;

use Symfony\Component\HttpFoundation\Response;

class RequestValidationException extends \Exception
{
    private const MESSAGE = 'Request validation exception';

    /**
     * @param array<string|int, array<string, string|null>>|array<int, array<string, mixed>>|array<string, string|null> $errors
     */
    public function __construct(
        private readonly array $errors
    ) {
        parent::__construct(self::MESSAGE, Response::HTTP_BAD_REQUEST);
    }

    public static function withError(string $message, ?string $property = null): self
    {
        return new self([['property' => $property, 'message' => $message]]);
    }

    /**
     * @return array<string|int, array<string, string|null>>|array<int, array<string, mixed>>|array<string, string|null>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
