<?php

namespace App\Model\Error;

class ValidationErrorItem
{
    public function __construct(
        private readonly string $field,
        private readonly string $message
    )
    {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}