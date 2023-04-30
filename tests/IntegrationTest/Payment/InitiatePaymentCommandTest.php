<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTest\Payment;

use App\Application\Configuration\Command\InvalidCommandException;
use App\Application\Payment\GetPayment\GetPaymentQuery;
use App\Application\Payment\GetPayment\PaymentDTO;
use App\Application\Payment\InitiatePayment\DTO\PayerDTO;
use App\Application\Payment\InitiatePayment\InitiatePaymentCommand;
use App\Domain\Currency;
use App\Domain\Payment\PaymentType;
use App\Tests\IntegrationTest\IntegrationTestCase;

class InitiatePaymentCommandTest extends IntegrationTestCase
{
    public function testThatInitiatesNewPayment(): void
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

        $this->assertEquals($paymentId, $payment->id);
        $this->assertEquals(Currency::PLN->value, $payment->currency);
        $this->assertEquals(100, $payment->amount);
        $this->assertEquals(PaymentType::OTHER->value, $payment->type);
        $this->assertEquals('uniqueRef', $payment->uniqueReference);
        $this->assertEquals('payerRef', $payment->payer->reference);
        $this->assertEquals('payer@email.com', $payment->payer->email);
        $this->assertEquals('Payer', $payment->payer->name);
        $this->assertNull($payment->bankId);
        $this->assertFalse($payment->isBankReadOnly);
    }

    public function testThatUpdatesPayerDataWhenPaymentHasExistingPayer(): void
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

        $anotherPaymentId = $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRef',
            new PayerDTO(
                'payerRef',
                'new-payer@email.com',
                'New Payer'
            ),
            null
        ));

        /** @var PaymentDTO $payment */
        $anotherPayment = $this->gatewayModule->executeQuery(new GetPaymentQuery($anotherPaymentId));

        $this->assertEquals('payerRef', $anotherPayment->payer->reference);
        $this->assertEquals('new-payer@email.com', $anotherPayment->payer->email);
        $this->assertEquals('New Payer', $anotherPayment->payer->name);
    }

    public function testThatThrowsInvalidCommandExceptionIfUniqueReferenceHasOverThan16Characters(): void
    {
        $this->expectException(InvalidCommandException::class);
        $this->expectExceptionMessage('Invalid command exception');

        $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRefMoreThanSixteenCharacters',
            new PayerDTO(
                'payerRef',
                'payer@email.com',
                'Payer'
            ),
            null
        ));
    }

    public function testThatThrowsInvalidCommandExceptionIfUniqueReferenceHasNonAlphanumericCharacters(): void
    {
        $this->expectException(InvalidCommandException::class);
        $this->expectExceptionMessage('Invalid command exception');

        $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRef#$',
            new PayerDTO(
                'payerRef',
                'payer@email.com',
                'Payer'
            ),
            null
        ));
    }

    public function testThatThrowsInvalidCommandExceptionIfPayerEmailIsNotAValidEmail(): void
    {
        $this->expectException(InvalidCommandException::class);
        $this->expectExceptionMessage('Invalid command exception');

        $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRef',
            new PayerDTO(
                'payerRef',
                'invalid-email',
                'Payer'
            ),
            null
        ));
    }
}
