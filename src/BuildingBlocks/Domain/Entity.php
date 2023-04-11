<?php

declare(strict_types=1);

namespace App\BuildingBlocks\Domain;

class Entity
{
    /** @var DomainEventInterface[] */
    private array $domainEvents = [];

    public function addDomainEvent(DomainEventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }

    /**
     * @return DomainEventInterface[]
     */
    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }

    /**
     * @throws BusinessRuleValidationException
     */
    protected function checkRule(BusinessRuleInterface $rule): void
    {
        if ($rule->isBroken()) {
            throw new BusinessRuleValidationException($rule);
        }
    }

    /**
     * @throws BusinessRuleValidationException
     */
    protected function checkRules(BusinessRuleInterface ...$rules): void
    {
        foreach ($rules as $rule) {
            $this->checkRule($rule);
        }
    }
}
