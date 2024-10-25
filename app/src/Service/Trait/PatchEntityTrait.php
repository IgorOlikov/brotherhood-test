<?php

namespace App\Service\Trait;

use App\DTO\Interface\DtoInterface;
use App\Entity\Interface\EntityInterface;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait PatchEntityTrait
{
    private int $newValuesCount = 0;

    public function patchEntityFromDto(string $entityClass, DtoInterface $requestDto): EntityInterface
    {
        if (!new $entityClass instanceof EntityInterface) {
            throw new RuntimeException();
        }

        $entity = $this->entityManager->getRepository($entityClass)->findOneBy(['id' => $requestDto->id]);

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach (get_object_vars($requestDto) as $property => $value) {
            if ($propertyAccessor->isWritable($entity, $property) && $value !== null) {
                if ($property === 'id') {
                    continue;
                }

                $propertyAccessor->setValue($entity, $property, $value);

                $this->newValuesCount++;
            }
        }

        if ($this->newValuesCount >= 1) {
            $this->entityManager->flush();
        }

        return $entity;
    }

}