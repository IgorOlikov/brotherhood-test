<?php

namespace App\Service;

use App\Entity\Project;
use App\Exception\ProjectAlreadyExistsException;
use App\Exception\ProjectAlreadyHasThisEmployeeException;
use App\Exception\ProjectDoesNotHaveThisEmployeeException;
use App\Exception\ProjectNotFoundException;
use App\Model\IdResponse;
use App\Model\ProjectListResponse;
use App\Model\ProjectModel;
use App\Repository\ProjectRepository;
use App\Validation\ProjectRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly EmployeeService $employeeService,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function addEmployeeToProject(int $projectId, int $employeeId): void
    {
        $employee = $this->employeeService->getEmployeeById($employeeId);

        $project = $this->getProjectById($projectId);

        if ($project->getEmployees()->contains($employee)) {
           throw new ProjectAlreadyHasThisEmployeeException();
        }

        $project->getEmployees()->add($employee);

        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    public function removeEmployeeFromProject(int $projectId, int $employeeId): void
    {
        $employee = $this->employeeService->getEmployeeById($employeeId);

        $project = $this->getProjectById($projectId);

        if (!$project->getEmployees()->contains($employee)) {
            throw new ProjectDoesNotHaveThisEmployeeException();
        }

        $project->getEmployees()->removeElement($employee);

        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }


    public function getProjectEmployees(int $projectId): ArrayCollection|Collection
    {
        $project = $this->getProjectById($projectId);

        return $project->getEmployees();
    }

    public function getProjects(): ProjectListResponse
    {
        $projects = $this->projectRepository->findAll();

        $items = array_map(
            fn (Project $project) => new ProjectModel(
                $project->getId(),
                $project->getName(),
                $project->getClient()
            ),
            $projects
        );

        return new ProjectListResponse($items);
    }

    public function updateProject(int $projectId, ProjectRequest $request): void
    {
        $project = $this->getProjectById($projectId);

        $this->upsertProject($project, $request);
    }

    public function getProjectById(int $projectId): Project
    {
        $project = $this->projectRepository->getById($projectId);

        if (empty($project)) {
            throw new ProjectNotFoundException(
                404,
                sprintf("Project with id %d does not exists", $projectId)
            );
        }

        return $project;
    }

    public function createProject(ProjectRequest $request): IdResponse
    {
        $project = new Project();

        $this->upsertProject($project, $request);

        return new IdResponse($project->getId());
    }

    public function deleteProject(int $projectId): void
    {
        $project = $this->getProjectById($projectId);

        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }

    public function upsertProject(Project $project, ProjectRequest $request): void
    {
        if ($this->projectRepository->existsByName($request->getName())) {
            throw new ProjectAlreadyExistsException();
        }

        $project->setName($request->getName());
        $project->setClient($request->getClient());


        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

}