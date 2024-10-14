<?php

namespace App\Model;

class EmployeesListResponse
{
    public function __construct(
        private readonly array $employees
    )
    {
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }
}