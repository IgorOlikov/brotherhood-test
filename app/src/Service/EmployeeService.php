<?php

namespace App\Service;

use App\DTO\EmployeeDto;
use App\Entity\Employee;
use App\Entity\Interface\EntityInterface;
use App\Service\Trait\PatchEntityTrait;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    use PatchEntityTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function createEmployeeFromDto(EmployeeDto $employeeDto): Employee
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

    public function patchEmployeeFromDto(EmployeeDto $employeeDto): EntityInterface
    {
        $employeeEntity = $this->entityManager->getRepository(Employee::class)->findOneBy(['id' => $employeeDto->id]);

        if (count(get_object_vars($employeeDto)) > 2) {
            $employeeEntity = $this->patchEntityFromDto($employeeEntity, $employeeDto);

            $this->entityManager->flush();
        }

        return $employeeEntity;
    }

}