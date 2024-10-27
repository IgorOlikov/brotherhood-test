<?php

namespace App\Controller;

use App\DTO\ProjectDto;
use App\Entity\Project;
use App\Resolver\PatchRequestPayloadResolver;
use App\Service\ProjectService;
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
class ProjectController extends AbstractController
{
    private array $context = [
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        AbstractNormalizer::GROUPS => ['public'],
        AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => false,
        AbstractObjectNormalizer::SKIP_NULL_VALUES => false
    ];

    public function __construct(
        private readonly ProjectService $projectService
    )
    {
    }

    #[Route('/project', name: 'app_project', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $projects = $this->projectService->getProjects();

        return $this->json($projects, context: $this->context);
    }

    #[Route('/project/{slug}', name: 'app_project_show', methods: ['GET'])]
    public function show(
        #[MapEntity(class: Project::class, mapping: ['slug' => 'slug'])]
        Project $project
    ): Response
    {
        return $this->json($project, context: $this->context);
    }

    #[Route('/project/create', name: 'app_project_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['create'])]
        ProjectDto $projectDto
    ): Response
    {
        $project = $this->projectService->createProjectFromDto($projectDto);

        return $this->json($project, context: $this->context);
    }

    #[Route('/project/update', name: 'app_employee_update', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['update'])]
        ProjectDto $projectDto
    ): Response
    {
        $project = $this->projectService->updateProjectFromDto($projectDto);

        return $this->json($project, context: $this->context);
    }

    #[Route('/project/patch', name: 'app_project_patch', methods: ['PATCH'])]
    public function patch(
        #[MapRequestPayload(acceptFormat: 'json', resolver: PatchRequestPayloadResolver::class)]
        ProjectDto $projectDto
    ): Response
    {
        $project = $this->projectService->patchProjectFromDto($projectDto);

        return $this->json($project, context: $this->context);
    }

    #[Route('/project/delete/{slug}', name: 'app_project_delete', methods: ['DELETE'])]
    public function delete(
        #[MapEntity(class: Project::class, mapping: ['slug' => 'slug'])]
        Project $project
    ): Response
    {
        $this->projectService->deleteProject($project);

        return $this->json(['status' => 'success']);
    }




}
