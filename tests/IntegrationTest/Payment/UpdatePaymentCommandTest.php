<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTest\Payment;

use App\Application\Configuration\Command\InvalidCommandException;
use App\Application\Payment\GetPayment\GetPaymentQuery;
use App\Application\Payment\GetPayment\PaymentDTO;
use App\Application\Payment\InitiatePayment\DTO\PayerDTO;
use App\Application\Payment\InitiatePayment\InitiatePaymentCommand;
use App\Application\Payment\UpdatePayment\UpdatePaymentCommand;
use App\Domain\Currency;
use App\Domain\Payment\Exception\PaymentNotFoundException;
use App\Domain\Payment\PaymentType;
use App\Tests\IntegrationTest\IntegrationTestCase;
use Symfony\Component\Uid\Uuid;

class UpdatePaymentCommandTest extends IntegrationTestCase
{
    public function testThatUpdatesPayment(): void
    {
        $paymentId = $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRef',
            new PayerDTO(
                'payerRef',
                'payer@email.com',
                'Payer'
            ),
            null
        ));

        /** @var PaymentDTO $payment */
        $payment = $this->gatewayModule->executeQuery(new GetPaymentQuery($paymentId));

        $this->assertEquals('payerRef', $payment->payer->reference);
        $this->assertEquals('payer@email.com', $payment->payer->email);
        $this->assertEquals('Payer', $payment->payer->name);
        $this->assertNull($payment->bankId);

        $this->gatewayModule->executeCommand(new UpdatePaymentCommand(
            $paymentId,
            new \App\Application\Payment\UpdatePayment\DTO\PayerDTO(
                'payerRef',
                'new-payer@email.com',
                'New Payer'
            ),
            Uuid::fromString('5e33e5c7-ab7f-448a-822d-9594bbb3683c')
        ));

        /** @var PaymentDTO $payment */
        $payment = $this->gatewayModule->executeQuery(new GetPaymentQuery($paymentId));

        $this->assertEquals('payerRef', $payment->payer->reference);
        $this->assertEquals('new-payer@email.com', $payment->payer->email);
        $this->assertEquals('New Payer', $payment->payer->name);
        $this->assertEquals('5e33e5c7-ab7f-448a-822d-9594bbb3683c', $payment->bankId);
    }

    public function testThatThrowsPaymentNotFoundExceptionWhenPaymentDoesNotExist(): void
    {
        $paymentId = Uuid::fromString('19c3fd2c-82d1-4688-b46a-4897b2035114');

        $this->expectException(PaymentNotFoundException::class);
        $this->expectExceptionMessage(\sprintf('Payment with id \'%s\' not found', $paymentId));

        $this->gatewayModule->executeCommand(new UpdatePaymentCommand(
            $paymentId,
            new \App\Application\Payment\UpdatePayment\DTO\PayerDTO(
                'payerRef',
                'new-payer@email.com',
                'New Payer'
            ),
            Uuid::fromString('5e33e5c7-ab7f-448a-822d-9594bbb3683c')
        ));
    }

    public function testThatThrowsInvalidCommandExceptionIfPayerEmailIsNotAValidEmail(): void
    {
        $paymentId = $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRef',
            new PayerDTO(
                'payerRef',
                'payer@email.com',
                'Payer'
            ),
            null
        ));

        $this->expectException(InvalidCommandException::class);
        $this->expectExceptionMessage('Invalid command exception');

        $this->gatewayModule->executeCommand(new UpdatePaymentCommand(
            $paymentId,
            new \App\Application\Payment\UpdatePayment\DTO\PayerDTO(
                'payerRef',
                'invalid-email',
                'New Payer'
            ),
            null,
        ));
    }
}
