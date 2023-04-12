<?php

declare(strict_types=1);

namespace App\UserInterface\Request\Validation;

use App\UserInterface\Request\RequestInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestValidator implements RequestValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(RequestInterface $request): void
    {
        $constraintViolationList = $this->validator->validate($request);
        $constraintViolations = $this->transformConstraintViolationListToArray($constraintViolationList);

        if (\count($constraintViolations) > 0) {
            $errors = \array_map(static function(ConstraintViolationInterface $constraintViolation) {
                $errorPath = \explode('.', $constraintViolation->getPropertyPath());

                return [
                    'property' => \end($errorPath),
                    'message' => $constraintViolation->getMessage(),
                ];
            }, $constraintViolations);

            throw new RequestValidationException($errors);
        }
    }

    /**
     * @return ConstraintViolationInterface[]
     */
    private function transformConstraintViolationListToArray(ConstraintViolationListInterface $violationList): array
    {
        $violations = [];

        foreach ($violationList as $violation) {
            $violations[] = $violation;
        }

        return $violations;
    }
}
