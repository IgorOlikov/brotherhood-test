<?php

namespace App\ArgumentResolver;

use App\Attribute\ValidateBodyRequest;
use App\Exception\InvalidRequestBodyJsonStructureException;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateBodyRequestArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(ValidateBodyRequest::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        try {
            $validatedRequest = $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                JsonEncoder::FORMAT
            );
        } catch (\Throwable $throwable) {
            throw new InvalidRequestBodyJsonStructureException('Invalid Json', $throwable);
        }

        $errors = $this->validator->validate($validatedRequest);

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return [$validatedRequest];
    }
}