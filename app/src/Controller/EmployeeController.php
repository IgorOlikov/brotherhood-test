<?php

namespace App\Controller;

use App\DTO\EmployeeDto;
use App\Entity\Employee;
use App\Resolver\PatchRequestPayloadResolver;
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
        private readonly EmployeeService $employeeService
    )
    {
    }

    #[Route('/employee', name: 'app_employee', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getEmployees();

        return $this->json(
            ['employees' => $employees],
            context: $this->context
        );
    }

    #[Route('/employee/{slug}', name: 'app_employee_show', methods: ['GET'])]
    public function show(
        #[MapEntity(class: Employee::class, mapping: ['slug' => 'slug'])]
        Employee $employee
    ): Response
    {
        return $this->json(
            $employee,
            context: $this->context
        );
    }

    #[Route('/employee/create', name: 'app_employee_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationGroups: ['create']
        )] EmployeeDto $employeeDto
    ): Response
    {
        $employee = $this->employeeService->createEmployeeFromDto($employeeDto);

        return $this->json(
            $employee,
            201,
            context: $this->context
        );
    }

    #[Route('/employee/patch', name: 'app_employee_patch', methods: ['PATCH'])]
    public function patch(
        #[MapRequestPayload(
            acceptFormat: 'json',
            resolver: PatchRequestPayloadResolver::class
        )] EmployeeDto $employeeDto
    ): Response
    {
        $employee = $this->employeeService->patchEmployeeFromDto($employeeDto);

        return $this->json(
            $employee,
            context: $this->context
        );
    }
}
