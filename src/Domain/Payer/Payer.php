<?php

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

    private ?\DateTimeImmutable $updatedAt;

    public static function createNew(string $reference, ?string $email, ?string $name): self
    {
        return new Payer($reference, $email, $name);
    }

    public function update(?string $email, ?string $name)
    {
        $this->email = $email ?? $this->email;
        $this->name = $name ?? $this->name;
    }

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
}
