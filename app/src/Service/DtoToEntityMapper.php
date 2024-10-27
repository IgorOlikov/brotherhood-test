<?php

namespace App\Service;

use App\DTO\Interface\DtoInterface;
use App\Entity\Interface\EntityInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

readonly class DtoToEntityMapper
{
    public function __construct(
        private PropertyAccessorInterface $propertyAccessor
    )
    {
    }

    public function map(DtoInterface $dto, EntityInterface $entity): EntityInterface
    {
        foreach (get_object_vars($dto) as $property => $value) {
            if ($this->propertyAccessor->isWritable($entity, $property) && $value !== null) {
                if ($property === 'id') {
                    continue;
                }

                $this->propertyAccessor->setValue($entity, $property, $value);
            }
        }

        return $entity;
    }

}