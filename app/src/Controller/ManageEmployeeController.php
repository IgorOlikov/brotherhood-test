<?php

namespace App\Controller;



use App\Attribute\ValidateBodyRequest;
use App\Entity\Employee;
use App\Exception\EmployeeNotFoundException;
use App\Service\EmployeeService;
use App\Validation\EmployeeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ManageEmployeeController extends AbstractController
{

    public function __construct(
        private readonly EmployeeService $employeeService
    )
    {
    }

    #[Route('/manage/employee', methods: ['GET'])]
    public function index(): Response
    {
        $employees = $this->employeeService->getEmployees();

        return $this->json($employees);
    }

    #[Route('manage/employee', methods: ['POST'])]
    public function create(
        #[ValidateBodyRequest] EmployeeRequest $request
    ): Response
    {
        $idResponse = $this->employeeService->creteEmployee($request);

        return $this->json($idResponse, 201);
    }


    #[Route('/manage/employee/update/{employeeId}', methods: ['PUT'])]
    public function update(
        #[ValidateBodyRequest] EmployeeRequest $request,
        int $employeeId
    ): Response
    {
        $this->employeeService->updateEmployee($employeeId, $request);

        return $this->json(
            json_encode([
                'status' => 'success',
                'id' => $employeeId,
                'message' => sprintf("Project with id %d successfully updated", $employeeId)
            ]),
            200);
    }

    #[Route(
        '/manage/employee/{employeeId}/add-project/{projectId}',
        requirements: ['employeeId' => Requirement::DIGITS, 'projectId' => Requirement::DIGITS],
        methods: ['POST']
    )]
    public function addProject(int $employeeId,int  $projectId): Response
    {
        $this->employeeService->addProjectToEmployee($employeeId, $projectId);

        return $this->json(
            json_encode([
                'status' => 'success',
                'message' => sprintf("Project with id %id successfully added to employee", $projectId)
            ]),
            200);
    }

    #[Route('/manage/employee/{employeeId}/remove-from-project/{projectId}', methods: ['DELETE'])]
    public function removeProject(int $employeeId, int $projectId): Response
    {
        $this->employeeService->removeProjectFromEmployee($employeeId, $projectId);

        return $this->json(
            json_encode([
                'status' => 'success',
                'message' => sprintf("Employee with id %id successfully removed from project", $employeeId)
            ]),
            200);
    }

    #[Route('/manage/employee/{employeeId}/delete', methods: ['DELETE'])]
    public function deleteEmployee(int $employeeId): Response
    {
        $this->employeeService->deleteEmployee($employeeId);

        return $this->json(
            json_encode([
                'status' => 'success',
                'message' => sprintf("Employee with id %d successfully deleted", $employeeId)
            ]),
            200);
    }

    #[Route('/manage/employee/{employeeId}/projects', methods: ['GET'])]
    public function employeeProjects(int $employeeId): Response
    {
       $projects = $this->employeeService->getEmployeeProjects($employeeId);

       return $this->json($projects);
    }



}
