<?php

namespace App\Service;

use App\DTO\EmployeeDto;
use App\Entity\Employee;
use App\Entity\Interface\EntityInterface;
use App\Entity\Project;
use App\Repository\EmployeeRepository;
use App\Service\Trait\PatchEntityTrait;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    use PatchEntityTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EmployeeRepository $employeeRepository,
        private readonly DtoToEntityMapper $dtoToEntityMapper,
    )
    {
    }

    public function getEmployees(): array
    {
        return $this->employeeRepository->findAll();
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

    public function employeeHasProject(Employee $employee, Project $project): bool
    {
        return $this->employeeRepository->employeeHasProjectById($employee->getId(), $project->getId());
    }

    public function addProjectToEmployee(Employee $employee, Project $project): void
    {
        $employee->addProject($project);

        $this->entityManager->flush();
    }

    public function removeProjectFromEmployee(Employee $employee, Project $project): void
    {
        $employee->removeProject($project);

        $this->entityManager->flush();
    }


}