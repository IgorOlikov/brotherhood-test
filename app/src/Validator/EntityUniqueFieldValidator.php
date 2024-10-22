<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EntityUniqueFieldValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityUniqueField) {
            throw new UnexpectedTypeException($constraint, EntityUniqueField::class);
        }

        if (null === $value || '' === $value) {
            return;  // Пропускаем пустые значения
        }

        if (!is_scalar($value)) {
            throw new UnexpectedValueException($value, 'scalar');
        }

        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $existingEntity = $repository->findOneBy([$constraint->field => $value]);

        if ($existingEntity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
