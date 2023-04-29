<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Configuration\Command\InvalidCommandException;
use App\Application\Contract\CommandInterface;
use App\Infrastructure\Configuration\Decorator\ValidationCommandHandlerDecorator;
use App\Tests\UnitTest\UnitTestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationCommandHandlerDecoratorTest extends UnitTestCase
{
    public function testThatValidatesCommandAndReturnsResult(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $inner = $this->getMockBuilder(CommandHandlerInterface::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $inner->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->willReturn('result');

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->with($command)
            ->willReturn($this->createStub(ConstraintViolationListInterface::class));

        $decorator = new ValidationCommandHandlerDecorator($inner, $validator);

        $result = $decorator->__invoke($command);

        $this->assertEquals('result', $result);
    }

    public function testThatThrowsInvalidCommandExceptionIfCommandIsNotValid(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $inner = $this->getMockBuilder(CommandHandlerInterface::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $inner->expects($this->never())
            ->method('__invoke')
            ->with($command)
            ->willReturn('result');

        $constraintViolationList = new ConstraintViolationList();

        $constraintViolation = $this->createStub(ConstraintViolationInterface::class);
        $constraintViolation->method('getPropertyPath')->willReturn('property');
        $constraintViolation->method('getMessage')->willReturn('message');

        $constraintViolationList->add($constraintViolation);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->with($command)
            ->willReturn($constraintViolationList);

        $decorator = new ValidationCommandHandlerDecorator($inner, $validator);

        $this->expectException(InvalidCommandException::class);
        $this->expectExceptionMessage('Invalid command exception');
        $this->expectExceptionObject(new InvalidCommandException([
            [
                'property' => 'property',
                'message' => 'message',
            ],
        ]));

        $decorator->__invoke($command);
    }
}
