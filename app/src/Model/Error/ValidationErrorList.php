<?php

namespace App\Model\Error;

class ValidationErrorList
{
    /**
     * @var ErrorValidationDetailsItem[]
     */
    private array $errors = [];

    public function addViolation(string $field, string $message): void
    {
        $this->errors[] = new ValidationErrorItem($field, $message);
    }

    /**
     * @return ErrorValidationDetailsItem[]
     */
    public function getViolations(): array
    {
        return $this->errors;
    }

}