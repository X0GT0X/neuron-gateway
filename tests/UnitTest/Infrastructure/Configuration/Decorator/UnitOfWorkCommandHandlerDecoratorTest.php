<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use App\Infrastructure\Configuration\Decorator\UnitOfWorkCommandHandlerDecorator;
use App\Tests\UnitTest\UnitTestCase;
use Neuron\BuildingBlocks\Infrastructure\UnitOfWorkInterface;

class UnitOfWorkCommandHandlerDecoratorTest extends UnitTestCase
{
    public function testThatCallsUnitOfWorkCommitAfterInvokingCommand(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $inner = $this->getMockBuilder(CommandHandlerInterface::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $inner->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->willReturn('result');

        $unitOfWork = $this->createMock(UnitOfWorkInterface::class);
        $unitOfWork->expects($this->once())
            ->method('commit');

        $decorator = new UnitOfWorkCommandHandlerDecorator($inner, $unitOfWork);

        $result = $decorator->__invoke($command);

        $this->assertEquals('result', $result);
    }
}
