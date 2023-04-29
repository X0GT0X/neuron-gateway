<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use App\Infrastructure\Configuration\Decorator\LoggingCommandHandlerDecorator;
use App\Tests\UnitTest\UnitTestCase;
use Neuron\BuildingBlocks\Domain\BusinessRuleInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Psr\Log\LoggerInterface;

class LoggingCommandHandlerDecoratorTest extends UnitTestCase
{
    public function testThatLogsCommandStartAndFinish(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $inner = $this->getMockBuilder(CommandHandlerInterface::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $inner->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->willReturn('result');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('info')
            ->with($this->logicalOr(
                $this->equalTo(\sprintf('Executing command %s', \get_class($command))),
                $this->equalTo(\sprintf('Command %s processed successfully', \get_class($command))),
            ));

        $decorator = new LoggingCommandHandlerDecorator($inner, $logger);

        $result = $decorator->__invoke($command);

        $this->assertEquals('result', $result);
    }

    public function testThatLogsCommandFailure(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $brokenRule = $this->createStub(BusinessRuleInterface::class);
        $exception = new BusinessRuleValidationException($brokenRule);

        $inner = $this->getMockBuilder(CommandHandlerInterface::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $inner->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->willThrowException($exception);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with(\sprintf('Command %s processing failed', \get_class($command)), [
                'exception' => $exception,
            ]);

        $decorator = new LoggingCommandHandlerDecorator($inner, $logger);

        $this->expectExceptionObject($exception);
        $decorator->__invoke($command);
    }

    public function testThatLogsUnexpectedError(): void
    {
        $command = $this->createStub(CommandInterface::class);
        $exception = $this->createStub(\Exception::class);

        $inner = $this->getMockBuilder(CommandHandlerInterface::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $inner->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->willThrowException($exception);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('critical')
            ->with(\sprintf('Command %s processing failed with unexpected error', \get_class($command)), [
                'exception' => $exception,
            ]);

        $decorator = new LoggingCommandHandlerDecorator($inner, $logger);

        $this->expectExceptionObject($exception);
        $decorator->__invoke($command);
    }
}
