<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTest;

use App\Application\Contract\GatewayModuleInterface;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected GatewayModuleInterface $gatewayModule;

    protected Connection $connection;

    protected TransportInterface $outboxTransport;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->gatewayModule = $container->get(GatewayModuleInterface::class);
        $this->connection = $container->get(Connection::class);
        $this->outboxTransport = $container->get('messenger.transport.outbox');
    }
}
