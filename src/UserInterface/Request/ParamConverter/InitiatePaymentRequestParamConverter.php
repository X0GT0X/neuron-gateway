<?php

declare(strict_types=1);

namespace App\UserInterface\Request\ParamConverter;

use App\UserInterface\Request\InitiatePaymentRequest;
use App\UserInterface\Request\Validation\RequestValidationException;
use App\UserInterface\Request\Validation\RequestValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

readonly class InitiatePaymentRequestParamConverter implements ParamConverterInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private RequestValidatorInterface $validator,
    ) {
    }

    /**
     * @throws RequestValidationException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $deserializedRequest = $this->serializer->deserialize($request->getContent(), InitiatePaymentRequest::class, 'json');

        $this->validator->validate($deserializedRequest);

        $request->attributes->set($configuration->getName(), $deserializedRequest);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return InitiatePaymentRequest::class === $configuration->getClass();
    }
}
