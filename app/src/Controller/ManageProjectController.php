<?php

namespace App\Controller;

use App\Attribute\ValidateBodyRequest;
use App\Service\ProjectService;
use App\Validation\ProjectRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ManageProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    )
    {
    }


    #[Route('/manage/project', methods: ['GET'])]
    public function index(): Response
    {
       $projects = $this->projectService->getProjects();

       return $this->json($projects);
    }


    #[Route('/manage/project/create', methods: ['POST'])]
    public function createProject(
        #[ValidateBodyRequest] ProjectRequest $request
    ): Response
    {
        $project = $this->projectService->createProject($request);

        return $this->json($project->getId());
    }

    #[Route('/manage/project/{projectId}/update', methods: ['PUT'])]
    public function update(
        #[ValidateBodyRequest] ProjectRequest $request,
        int $projectId
    ): Response
    {
        $this->projectService->updateProject($projectId, $request);

        return $this->json(
            json_encode([
                'status' => 'success',
                'id' => $projectId,
                'message' => sprintf("Project with id %d successfully updated", $projectId)
            ]),
            200);
    }


    #[Route('/manage/project/delete/{projectId}', methods: ['DELETE'])]
    public function deleteProject(int $projectId): Response
    {
        $this->projectService->deleteProject($projectId);

         return $this->json(
             json_encode([
                 'status' => 'success',
                 'message' => sprintf("Project with id %d successfully deleted", $projectId)
             ]),
             200);
    }

    #[Route('/manage/project/projectId/add-employee/{employeeId}', methods: ['POST'])]
    public function addEmployeeToProject(int $projectId, int $employeeId): Response
    {
        $this->projectService->addEmployeeToProject($projectId, $employeeId);

        return $this->json(
            json_encode([
                'status' => 'success',
                'message' => sprintf("Employee with id %d successfully added to project", $employeeId)
            ]),
            200);
    }

    #[Route('/manage/project/{projectId}/remove-employee/{employeeId}', methods: ['DELETE'])]
    public function removeEmployeeFromProject(int $projectId, int $employeeId): Response
    {
        $this->projectService->removeEmployeeFromProject($projectId, $employeeId);

        return $this->json(
            json_encode([
                'status' => 'success',
                'message' => sprintf("Employee with id %d successfully removed from project", $employeeId)
            ]),
            200);
    }

    #[Route('/manage/project/{projectId}/employees', methods: ['GET'])]
    public function projectEmployees(int $projectId): Response
    {
        $employees = $this->projectService->getProjectEmployees($projectId);

        return $this->json($employees);
    }







}
