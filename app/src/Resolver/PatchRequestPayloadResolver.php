<?php

namespace App\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PatchRequestPayloadResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attribute = $argument->getAttributesOfType(MapRequestPayload::class, ArgumentMetadata::IS_INSTANCEOF)[0] ?? null;
        if (!$attribute) {
            return [];
        }

        $class = $argument->getType() ?? '';
        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Invalid class');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return [];
        }

        $dto = $class::hydrate($data);

        $errors = $this->validator->validate(value: $dto, groups: ['patch']);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        return [$dto];
    }
}