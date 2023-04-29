<?php

namespace App\Tests\IntegrationTest\Payment;

use App\Application\Payment\GetPayment\GetPaymentQuery;
use App\Application\Payment\GetPayment\PaymentDTO;
use App\Application\Payment\InitiatePayment\DTO\PayerDTO;
use App\Application\Payment\InitiatePayment\InitiatePaymentCommand;
use App\Domain\Currency;
use App\Domain\Payment\Exception\PaymentNotFoundException;
use App\Domain\Payment\PaymentType;
use App\Tests\IntegrationTest\IntegrationTestCase;
use Symfony\Component\Uid\Uuid;

class GetPaymentQueryTest extends IntegrationTestCase
{
    public function testThat_ReturnsProperPaymentData(): void
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

    public function testThat_ThrowsPaymentNotFoundException_WhenPaymentDoesNotExist(): void
    {
        $paymentId = Uuid::fromString('cfc9fa5a-1412-4def-9730-3bdb2636318f');

        $this->expectException(PaymentNotFoundException::class);
        $this->expectExceptionMessage(\sprintf('Payment with id \'%s\' not found.', $paymentId));

        $this->gatewayModule->executeQuery(new GetPaymentQuery($paymentId));
    }
}
