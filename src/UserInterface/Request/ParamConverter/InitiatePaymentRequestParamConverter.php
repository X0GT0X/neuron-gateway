<?php

namespace App\UserInterface\Request\ParamConverter;

use App\UserInterface\Request\InitiatePaymentRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

readonly class InitiatePaymentRequestParamConverter implements ParamConverterInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $request->attributes->set(
            $configuration->getName(),
            $this->serializer->deserialize($request->getContent(), InitiatePaymentRequest::class, 'json'),
        );
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === InitiatePaymentRequest::class;
    }
}
