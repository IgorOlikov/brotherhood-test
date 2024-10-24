<?php

namespace App\DTO\Trait;

use DateTimeImmutable;
use ReflectionProperty;

trait HydratePatchTrait
{
    public static function hydrate(array $values): self
    {
        $dto = new self();

        foreach ($values as $key => $value) {
            if (property_exists($dto, $key)) {

                $dateType = (new ReflectionProperty($dto, $key))->gettype();

                if($dateType->getName() == DateTimeImmutable::class) {
                    $dto->$key = new DateTimeImmutable($value);
                } else {
                    $dto->$key = $value;
                }
            }
        }

        return $dto;
    }
}