<?php

namespace App\Infrastructure\Repository;

use App\Domain\Payer\Payer;
use App\Domain\Payer\PayerRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PayerRepository extends ServiceEntityRepository implements PayerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payer::class);
    }

    public function add(Payer $payer): void
    {
        $this->getEntityManager()->persist($payer);
    }

    public function update(Payer $payer): void
    {
        $this->getEntityManager()->getUnitOfWork()->scheduleForUpdate($payer);
    }
}
