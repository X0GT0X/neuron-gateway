<?php

declare(strict_types=1);

namespace App\Domain\Payer;

use App\BuildingBlocks\Domain\AggregateRootInterface;
use App\BuildingBlocks\Domain\Entity;
use App\Domain\Payer\Event\PayerCreatedDomainEvent;
use Symfony\Component\Uid\Uuid;

class Payer extends Entity implements AggregateRootInterface
{
    public PayerId $id;

    private string $reference;

    private ?string $email;

    private ?string $name;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private function __construct(string $reference, ?string $email, ?string $name)
    {
        $this->id = new PayerId(Uuid::v4());
        $this->reference = $reference;
        $this->email = $email;
        $this->name = $name;
        $this->createdAt = new \DateTimeImmutable();

        $this->addDomainEvent(new PayerCreatedDomainEvent(
            $this->id,
            $this->reference,
            $this->email,
            $this->name
        ));
    }

    public static function createNew(string $reference, ?string $email, ?string $name): self
    {
        return new self($reference, $email, $name);
    }

    public function update(?string $email, ?string $name): void
    {
        $this->email = $email ?? $this->email;
        $this->name = $name ?? $this->name;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
