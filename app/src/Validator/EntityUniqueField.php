<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class EntityUniqueField extends Constraint
{
    public string $message = 'The entity with field "{{ field }}" and value "{{ value }}" already exist.';
    public string $entityClass;
    public string $field;

    public function __construct(
        string $entityClass = 'entityClass',
        string $field = 'id',
        string $message = null,
        array $groups = null,
        mixed $payload = null
    )
    {
        parent::__construct([], groups: $groups, payload: $payload);

        $this->entityClass = $entityClass;
        $this->field = $field;

        if ($message !== null) {
            $this->message = $message;
        }
    }

    /*
    public function getDefaultOption(): string
    {
        return 'entityClass';
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass'];
    }
    */

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
