<?php

namespace App\Controller;

use App\DTO\EmployeeDto;
use App\Entity\Employee;
use App\Entity\Project;
use App\Resolver\PatchRequestPayloadResolver;
use App\Resolver\RedisEntityValueResolver;
use App\Service\EmployeeService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/api/v1')]
class EmployeeController extends AbstractController
{
    private array $context = [
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        AbstractNormalizer::GROUPS => ['public'],
        AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => false,
        AbstractObjectNormalizer::SKIP_NULL_VALUES => false
    ];

    public function __construct(
        private readonly EmployeeService $employeeService,
    )
    {
    }

    #[Route('/employee', name: 'app_employee_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getEmployees();

        return $this->json(['employees' => $employees], context: $this->context);
    }

    #[Route('/employee/{slug}', name: 'app_employee_show', methods: ['GET'])]
    public function show(
        #[MapEntity(class: Employee::class, mapping: ['slug' => 'slug'], resolver: RedisEntityValueResolver::class)]
        Employee|string $employee
    ): Response
    {
        return is_string($employee) ? new JsonResponse($employee, json: true) : $this->json($employee, context: $this->context);
    }

    #[Route('/employee', name: 'app_employee_store', methods: ['POST'])]
    public function store(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['create'])]
        EmployeeDto $employeeDto
    ): Response
    {
        $employee = $this->employeeService->createEmployeeFromDto($employeeDto);

        return $this->json($employee, 201, context: $this->context);
    }

    #[Route('/employee/{slug}', name: 'app_employee_update', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['update'])]
        EmployeeDto $employeeDto,
        #[MapEntity(class: Employee::class, mapping: ['slug' => 'slug'])]
        Employee $employee
    ): Response
    {
       $employee = $this->employeeService->updateEmployeeFromDto($employee, $employeeDto);

        return $this->json($employee, context: $this->context);
    }

    #[Route('/employee/{slug}', name: 'app_employee_patch', methods: ['PATCH'])]
    public function patch(
        #[MapRequestPayload(acceptFormat: 'json', resolver: PatchRequestPayloadResolver::class)]
        EmployeeDto $employeeDto,
        #[MapEntity(class: Employee::class, mapping: ['slug' => 'slug'])]
        Employee $employee
    ): Response
    {
        $employee = $this->employeeService->patchEmployeeFromDto($employee, $employeeDto);

        return $this->json($employee, context: $this->context);
    }

    #[Route('/employee/{slug}', name: 'app_employee_delete', methods: ['DELETE'])]
    public function delete(
        #[MapEntity(class: Employee::class, mapping: ['slug' => 'slug'])]
        Employee $employee
    ): Response
    {
        $this->employeeService->deleteEmployee($employee);

        return $this->json(['status' => 'success', 'message' => 'Employee successfully created']);
    }

    #[Route('/employee/{employeeSlug}/project/{projectSlug}', name: 'app_employee_add_project', methods: ['POST'])]
    public function addProjectToEmployee(
       #[MapEntity(class: Employee::class, mapping: ['employeeSlug' => 'slug'])] Employee $employee,
       #[MapEntity(class: Project::class, mapping: ['projectSlug' => 'slug'])] Project $project
    ): Response
    {
        return $this->employeeService->employeeHasProject($employee, $project)
            ? $this->json(['statues' => 'failed', 'message' => 'Employee already has this project'], 422)
            : $this->json(['status' => 'success', 'message' => 'Project successfully added to employee']);
    }

    #[Route('/employee/{employeeSlug}/project/{projectSlug}', name: 'app_employee_remove_project', methods: ['DELETE'])]
    public function removeProjectFromEmployee(
        #[MapEntity(class: Employee::class, mapping: ['employeeSlug' => 'slug'])] Employee $employee,
        #[MapEntity(class: Project::class, mapping: ['projectSlug' => 'slug'])] Project $project
    ): Response
    {
        if($this->employeeService->employeeHasProject($employee, $project)) {
            $this->employeeService->removeProjectFromEmployee($employee, $project);

            return $this->json(['status' => 'success', 'message' => 'Project successfully removed from employee']);
        }
        return $this->json(['statues' => 'failed', 'message' => 'The employee does not have this project'], 422);
    }

}
