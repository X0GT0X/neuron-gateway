<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Payment;

use App\Domain\Currency;
use App\Domain\Payer\PayerId;
use App\Domain\Payment\Event\PaymentCreatedDomainEvent;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentType;
use App\Tests\UnitTest\UnitTestCase;

class PaymentTest extends UnitTestCase
{
    public function testThatSuccessfullyCreatesPayment(): void
    {
        $payerId = new PayerId('cfc9fa5a-1412-4def-9730-3bdb2636318f');

        $payment = Payment::createNew(
            Currency::PLN,
            100,
            PaymentType::OTHER,
            'uniqueRef',
            $payerId,
            null
        );

        /** @var PaymentCreatedDomainEvent $domainEvent */
        $domainEvent = $this->assertPublishedDomainEvents($payment, PaymentCreatedDomainEvent::class)[0];

        $this->assertEquals($payment->id, $domainEvent->paymentId);
        $this->assertEquals(100, $domainEvent->amount);
        $this->assertEquals(Currency::PLN, $domainEvent->currency);
        $this->assertEquals(PaymentType::OTHER, $domainEvent->paymentType);
        $this->assertEquals('uniqueRef', $domainEvent->uniqueReference);
        $this->assertEquals($payerId, $domainEvent->payerId);
        $this->assertNull($domainEvent->bankId);
    }
}
