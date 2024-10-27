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
        private readonly EntityManagerInterface $entityManager,
        private readonly DtoToEntityMapper $dtoToEntityMapper,
        private readonly string $targetEntityClass = Employee::class
    )
    {
    }

    public function getEmployees(): array
    {
        return $this->entityManager->getRepository($this->targetEntityClass)->findAll();
    }

    public function createEmployeeFromDto(EmployeeDto $employeeDto): EntityInterface
    {
        $employee = new Employee();

        $employee = $this->dtoToEntityMapper->map($employeeDto, $employee);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $employee;
    }

    public function updateEmployeeFromDto(Employee $employee, EmployeeDto $employeeDto): EntityInterface
    {
        $employee = $this->dtoToEntityMapper->map($employeeDto, $employee);

        $this->entityManager->flush();

        return $employee;
    }

    public function patchEmployeeFromDto(Employee $employee, EmployeeDto $employeeDto): EntityInterface
    {
        return $this->patchEntityFromDto($employee, $employeeDto);
    }

    public function deleteEmployee(Employee $employee): void
    {
        $this->entityManager->remove($employee);
        $this->entityManager->flush();
    }



}