<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Payment\Exception\PaymentNotFoundException;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentId;
use App\Domain\Payment\PaymentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PaymentRepository extends ServiceEntityRepository implements PaymentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function add(Payment $payment): void
    {
        $this->getEntityManager()->persist($payment);
    }

    public function get(PaymentId $id): Payment
    {
        $payment = $this->find($id);

        if (!$payment instanceof Payment) {
            throw new PaymentNotFoundException(\sprintf('Payment with id \'%s\' not found', $id->getValue()));
        }

        return $payment;
    }
}
