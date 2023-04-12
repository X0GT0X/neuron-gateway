<?php

declare(strict_types=1);

namespace App\UserInterface\Controller;

use App\Application\Contract\GatewayModuleInterface;
use App\Application\Payment\InitiatePayment\DTO\PayerDTO;
use App\Application\Payment\InitiatePayment\InitiatePaymentCommand;
use App\Domain\Currency;
use App\Domain\Payment\PaymentType;
use App\UserInterface\Request\InitiatePaymentRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final readonly class PaymentController
{
    public function __construct(
        private GatewayModuleInterface $gatewayModule
    ) {
    }

    #[Route('/payments', methods: ['POST'])]
    public function initiate(InitiatePaymentRequest $request): JsonResponse
    {
        $paymentId = $this->gatewayModule->executeCommand(new InitiatePaymentCommand(
            Currency::from((string) $request->currency),
            (int) $request->amount,
            PaymentType::from((string) $request->type),
            (string) $request->uniqueReference,
            new PayerDTO(
                (string) $request->payer?->reference,
                (string) $request->payer?->email,
                (string) $request->payer?->name,
            ),
            $request->bankId,
        ));

        return new JsonResponse([
            'paymentId' => $paymentId,
        ]);
    }
}
