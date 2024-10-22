<?php

namespace App\Service;

use App\DTO\ProjectDto;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function createProjectFromDto(ProjectDto $projectDto): Project
    {
        $project = new Project();

        $project->setName($projectDto->name);
        $project->setClient($projectDto->client);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }



}