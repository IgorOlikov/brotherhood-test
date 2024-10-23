<?php

namespace App\Controller;

use App\DTO\EmployeeDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class EmployeeController extends AbstractController
{
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
        dd($employeeDto);
    }
}
