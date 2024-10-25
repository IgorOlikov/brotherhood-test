<?php

namespace App\Controller;

use App\DTO\EmployeeDto;
use App\Resolver\PatchRequestPayloadResolver;
use App\Service\EmployeeService;
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
    public function __construct(
        private readonly EmployeeService $employeeService
    )
    {
    }

    #[Route('/employee', name: 'app_employee')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EmployeeController.php',
        ]);
    }

    #[Route('/employee/create', name: 'app_employee_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationGroups: ['create']
        )] EmployeeDto $employeeDto
    )
    {
        $employee = $this->employeeService->createEmployeeFromDto($employeeDto);

        return $this->json(['id' => $employee->getId()], 201);
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
            context: [
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
                AbstractNormalizer::GROUPS => ['public'],
                AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => true,
                AbstractObjectNormalizer::SKIP_NULL_VALUES => false
            ]
        );
    }
}
