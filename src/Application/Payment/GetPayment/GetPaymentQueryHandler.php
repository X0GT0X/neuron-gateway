<?php

declare(strict_types=1);

namespace App\Application\Payment\GetPayment;

use App\Domain\Payer\Exception\PayerNotFoundException;
use App\Domain\Payment\Exception\PaymentNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetPaymentQueryHandler
{
    private \Doctrine\DBAL\Connection $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    public function __invoke(GetPaymentQuery $query): PaymentDTO
    {
        $sql = '
            SELECT id, currency, amount, type, unique_reference, payer_id, bank_id, status, created_at, bank_is_read_only
            FROM payments p
            WHERE p.id = :id
        ';

        $statement = $this->connection->prepare($sql);
        $result = $statement->executeQuery(['id' => $query->paymentId])->fetchAssociative();

        if (false === $result) {
            throw new PaymentNotFoundException(\sprintf('Payment with id \'%s\' not found.', $query->paymentId));
        }

        $payer = $this->fetchPayer($result['payer_id']);

        return new PaymentDTO(
            $result['id'],
            $result['currency'],
            $result['amount'],
            $result['type'],
            $result['unique_reference'],
            $payer,
            $result['bank_id'],
            $result['status'],
            $result['created_at'],
            $result['bank_is_read_only']
        );
    }

    private function fetchPayer(string $id): PayerDTO
    {
        $sql = '
            SELECT reference, email, name
            FROM payers p
            WHERE p.id = :id
        ';

        $statement = $this->connection->prepare($sql);
        $result = $statement->executeQuery(['id' => $id])->fetchAssociative();

        if (false === $result) {
            throw new PayerNotFoundException(\sprintf('Payer with id \'%s\' not found.', $id));
        }

        return new PayerDTO(
            $result['reference'],
            $result['email'],
            $result['name'],
        );
    }
}
