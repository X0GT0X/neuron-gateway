<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Payer;

use App\Domain\Payer\Event\PayerCreatedDomainEvent;
use App\Domain\Payer\Payer;
use App\Domain\Payer\PayerCounterInterface;
use App\Domain\Payer\Rule\PayerReferenceShouldBeUniqueRule;
use App\Tests\UnitTest\UnitTestCase;

class PayerTest extends UnitTestCase
{
    public function testThatSuccessfullyCreatesPayer(): void
    {
        $payerCounter = $this->createMock(PayerCounterInterface::class);
        $payerCounter->expects($this->once())
            ->method('countByReference')
            ->with('reference')
            ->willReturn(0);

        $payer = Payer::createNew('reference', 'payer@email.com', 'Payer', $payerCounter);

        /** @var PayerCreatedDomainEvent $domainEvent */
        $domainEvent = $this->assertPublishedDomainEvents($payer, PayerCreatedDomainEvent::class)[0];

        $this->assertEquals($payer->id, $domainEvent->payerId);
        $this->assertEquals('reference', $domainEvent->reference);
        $this->assertEquals('payer@email.com', $domainEvent->email);
        $this->assertEquals('Payer', $domainEvent->name);
    }

    public function testThatCreatingPayerWithExistingReferenceBreaksPayerReferenceShouldBeUniqueRule(): void
    {
        $payerCounter = $this->createMock(PayerCounterInterface::class);
        $payerCounter->expects($this->once())
            ->method('countByReference')
            ->with('reference')
            ->willReturn(1);

        $this->expectBrokenRule(
            PayerReferenceShouldBeUniqueRule::class,
            'Payer with reference \'reference\' already exists',
            static function() use ($payerCounter): void {
                Payer::createNew('reference', 'payer@email.com', 'Payer', $payerCounter);
            }
        );
    }
}
