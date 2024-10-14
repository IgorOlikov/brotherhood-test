<?php

namespace App\Model;

class EmployeeModel
{
    private ?int $id = null;

    private ?string $fullName = null;

    private ?string $position = null;

    private ?string $email = null;

    private ?string $phoneNumber = null;

    private ?int $age = null;

    public function __construct(
        int $id,
        string $fullName,
        string $position,
        ?string $email = null,
        ?string $phoneNumber = null,
        ?string $age = null
    )
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->position = $position;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->age = $age;

    }
}