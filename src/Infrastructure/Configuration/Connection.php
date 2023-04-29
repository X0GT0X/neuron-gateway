<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration;

use App\Application\Configuration\Connection\ConnectionInterface;
use App\Application\Configuration\Connection\DTOTransformingException;
use App\Application\Configuration\Connection\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;

// TODO: move to neuron/building-blocks
readonly class Connection implements ConnectionInterface
{
    private \Doctrine\DBAL\Connection $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    public function fetchOne(string $sql, string $dtoClass, array $parameters = [])
    {
        try {
            $statement = $this->connection->prepare($sql);
            $result = $statement->executeQuery($parameters)->fetchAssociative();

            if (false === $result) {
                throw new NotFoundException('Data for given parameters not found');
            }

            return $this->transformSQLResultToDTO($result, $dtoClass);
        } catch (\ReflectionException $exception) {
            throw new DTOTransformingException(\sprintf('Unable to transform SQL result to \'%s\'. Reason: %s', $dtoClass, $exception->getMessage()), previous: $exception);
        }
    }

    public function fetchAll(string $sql, string $dtoClass, array $parameters = []): array
    {
        try {
            $statement = $this->connection->prepare($sql);
            $result = $statement->executeQuery($parameters)->fetchAllAssociative();

            return \array_map(
                fn ($singleResult) => $this->transformSQLResultToDTO($singleResult, $dtoClass),
                $result
            );
        } catch (\ReflectionException $exception) {
            throw new DTOTransformingException(\sprintf('Unable to transform SQL result to \'%s\'. Reason: %s', $dtoClass, $exception->getMessage()), previous: $exception);
        }
    }

    /**
     * @template T
     *
     * @param array<string, mixed> $result
     * @param class-string<T>      $dtoClass
     *
     * @throws \ReflectionException
     *
     * @return T
     */
    private function transformSQLResultToDTO(array $result, string $dtoClass)
    {
        $properties = [];

        foreach (\array_keys($result) as $propertyName) {
            $properties[$this->transformToCamelCase($propertyName)] = $result[$propertyName];
        }

        $reflection = new \ReflectionClass($dtoClass);

        return $reflection->newInstanceArgs($properties);
    }

    private function transformToCamelCase(string $propertyName): string
    {
        return \str_replace('_', '', \lcfirst(\ucwords($propertyName, '_')));
    }
}
