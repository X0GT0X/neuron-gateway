<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Payer;

use App\Domain\Payer\Event\PayerCreatedDomainEvent;
use App\Domain\Payer\Event\PayerUpdatedDomainEvent;
use App\Domain\Payer\Payer;
use App\Domain\Payer\PayerComposer;
use App\Domain\Payer\PayerCounterInterface;
use App\Domain\Payer\PayerRepositoryInterface;
use App\Tests\UnitTest\UnitTestCase;

class PayerComposerTest extends UnitTestCase
{
    public function testThatCreatesNewPayerIfPayerWithGivenReferenceNotFound(): void
    {
        $payerRepository = $this->createMock(PayerRepositoryInterface::class);
        $payerRepository->expects($this->once())
            ->method('findByReference')
            ->with('reference')
            ->willReturn(null);

        $payerRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Payer $payer): bool {
                /** @var PayerCreatedDomainEvent $domainEvent */
                $domainEvent = $payer->getDomainEvents()[0];

                $this->assertEquals('reference', $domainEvent->reference);
                $this->assertEquals('payer@email.com', $domainEvent->email);
                $this->assertEquals('Payer', $domainEvent->name);

                return true;
            }));

        $payerCounter = $this->createMock(PayerCounterInterface::class);
        $payerCounter->expects($this->once())
            ->method('countByReference')
            ->with('reference')
            ->willReturn(0);

        $payerComposer = new PayerComposer($payerRepository, $payerCounter);
        $payerComposer->compose('reference', 'payer@email.com', 'Payer');
    }

    public function testThatUpdatesExistingPayer(): void
    {
        $payerCounter = $this->createMock(PayerCounterInterface::class);
        $payerCounter->expects($this->once())
            ->method('countByReference')
            ->with('reference')
            ->willReturn(0);

        $payer = Payer::createNew('reference', 'payer@email.com', 'Payer', $payerCounter);

        $payerRepository = $this->createMock(PayerRepositoryInterface::class);
        $payerRepository->expects($this->once())
            ->method('findByReference')
            ->with('reference')
            ->willReturn($payer);

        $payerComposer = new PayerComposer($payerRepository, $payerCounter);
        $payerComposer->compose('reference', 'new-payer@email.com', 'New Payer');

        /** @var PayerUpdatedDomainEvent $domainEvent */
        $domainEvent = $this->assertPublishedDomainEvents($payer, PayerUpdatedDomainEvent::class)[0];

        $this->assertEquals('reference', $domainEvent->reference);
        $this->assertEquals('new-payer@email.com', $domainEvent->email);
        $this->assertEquals('New Payer', $domainEvent->name);
    }
}
