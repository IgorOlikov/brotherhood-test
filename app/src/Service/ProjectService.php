<?php

namespace App\Service;

use App\DTO\ProjectDto;
use App\Entity\Interface\EntityInterface;
use App\Entity\Project;
use App\Service\Trait\PatchEntityTrait;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    use PatchEntityTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DtoToEntityMapper $dtoToEntityMapper,
        private readonly string $targetEntityClass = Project::class
    )
    {
    }

    public function getProjects(): array
    {
        return $this->entityManager->getRepository($this->targetEntityClass)->findAll();
    }

    public function createProjectFromDto(ProjectDto $projectDto): EntityInterface
    {
        $project = new Project();

        $project = $this->dtoToEntityMapper->map($projectDto, $project);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function updateProjectFromDto(Project $project, ProjectDto $projectDto): EntityInterface
    {
        $project = $this->dtoToEntityMapper->map($projectDto, $project);

        $this->entityManager->flush();

        return $project;
    }

    public function patchProjectFromDto(Project $project, ProjectDto $projectDto): EntityInterface
    {
        return $this->patchEntityFromDto($project, $projectDto);
    }

    public function deleteProject(Project $project): void
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }

}