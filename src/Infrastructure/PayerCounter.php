<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Payer\PayerCounterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

readonly class PayerCounter implements PayerCounterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByReference(string $reference): int
    {
        $query = $this->entityManager->createQuery('SELECT count(p) FROM App\Domain\Payer\Payer p WHERE p.reference = :reference')
            ->setParameter('reference', $reference);

        return (int) ($query->getSingleScalarResult());
    }
}
