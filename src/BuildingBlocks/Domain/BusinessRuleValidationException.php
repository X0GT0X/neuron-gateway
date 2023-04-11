<?php

namespace App\BuildingBlocks\Domain;

class BusinessRuleValidationException extends \Exception
{
    private BusinessRuleInterface $brokenRule;

    private string $details;

    public function __construct(BusinessRuleInterface $brokenRule)
    {
        parent::__construct($brokenRule->getMessage());

        $this->brokenRule = $brokenRule;
        $this->details = $brokenRule->getMessage();
    }

    public function getBrokenRule(): BusinessRuleInterface
    {
        return $this->brokenRule;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s: %s',
            get_class($this->brokenRule),
            $this->brokenRule->getMessage()
        );
    }
}