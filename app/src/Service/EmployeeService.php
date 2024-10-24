<?php

namespace App\Service;

use App\DTO\EmployeeDto;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function createProjectFromDto(EmployeeDto $employeeDto): Employee
    {
        $employee = new Employee();

        $employee->setFullName($employeeDto->fullName);
        $employee->setEmail($employeeDto->email);
        $employee->setPosition($employeeDto->position);
        $employee->setPhoneNumber($employeeDto->phoneNumber);
        $employee->setDateOfBrith($employeeDto->dateOfBrith);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $employee;
    }

}