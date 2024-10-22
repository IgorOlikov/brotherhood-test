<?php

namespace App\Controller;

use App\DTO\ProjectDto;
use App\Repository\ProjectRepository;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    )
    {
    }

    #[Route('/project', name: 'app_project', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): JsonResponse
    {
        $projects = $projectRepository->findAll();

        return $this->json($projects);
    }

    #[Route('/project/create', name: 'app_project_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationGroups: ['create']
        )] ProjectDto $projectDto
    ): Response
    {
        $project = $this->projectService->createProjectFromDto($projectDto);

        return $this->json(['project' => $project->getId()], 201);
    }



}
