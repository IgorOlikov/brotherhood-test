<?php

namespace App\Service;

use App\Entity\Employee;
use App\Exception\EmployeeAlreadyExistsException;
use App\Exception\EmployeeAlreadyInProjectException;
use App\Exception\EmployeeNotFoundException;
use App\Exception\EmployeeNotInProjectException;
use App\Model\EmployeeModel;
use App\Model\EmployeesListResponse;
use App\Model\IdResponse;
use App\Repository\EmployeeRepository;
use App\Validation\EmployeeRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{

    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly ProjectService $projectService,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function getEmployeeProjects(int $employeeId): ArrayCollection|Collection
    {
        $employee = $this->getEmployeeById($employeeId);

        return $employee->getProjects();
    }

    public function creteEmployee(EmployeeRequest $request): IdResponse
    {
        $employee = new Employee();

        $this->upsertEmployee($employee, $request);

        return new IdResponse($employee->getId());
    }

    public function updateEmployee(int $employeeId, EmployeeRequest $request): void
    {
        $employee = $this->getEmployeeById($employeeId);

        $this->upsertEmployee($employee, $request);
    }

    public function addProjectToEmployee(int $employeeId, int $projectId): void
    {
        $employee = $this->getEmployeeById($employeeId);

        $project = $this->projectService->getProjectById($projectId);

        if ($employee->getProjects()->contains($project)) {
            throw new EmployeeAlreadyInProjectException();
        }

        $employee->addProject($project);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

    public function removeProjectFromEmployee(int $employeeId, int $projectId): void
    {
        $employee = $this->getEmployeeById($employeeId);

        $project = $this->projectService->getProjectById($projectId);

        if (!$employee->getProjects()->contains($project)) {
            throw new EmployeeNotInProjectException();
        }

        $employee->getProjects()->removeElement($project);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

    public function getEmployees(): EmployeesListResponse
    {
        $employees = $this->employeeRepository->findAll();

        $items = array_map(
            fn (Employee $employee) => new EmployeeModel(
                $employee->getId(),
                $employee->getFullName(),
                $employee->getPosition(),
                $employee->getEmail(),
                $employee->getPhoneNumber(),
                $employee->getAge()
            ),
            $employees
        );

        return new EmployeesListResponse($items);
    }

    public function getEmployeeById(int $employeeId): Employee
    {
        $employee = $this->employeeRepository->getById($employeeId);

        if (!$employee) {
            throw new EmployeeNotFoundException(
                404,
                sprintf("Employee with id %d not exists", $employeeId)
            );
        }
        return $employee;
    }

    public function deleteEmployee(int $employeeId): void
    {
        $employee = $this->getEmployeeById($employeeId);

        $this->entityManager->remove($employee);
        $this->entityManager->flush();
    }

    public function upsertEmployee(Employee $employee, EmployeeRequest $request): void
    {
        if ($this->employeeRepository->existsByName($request->getFullName())) {
            throw new EmployeeAlreadyExistsException();
        }

        $employee->setFullName($request->getFullName());
        $employee->setEmail($request->getEmail());
        $employee->setPosition($request->getPosition());
        $employee->setPhoneNumber($request->getPhoneNumber());
        $employee->setAge($request->getAge());

        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

}