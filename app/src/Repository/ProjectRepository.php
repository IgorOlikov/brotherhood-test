<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function existsByName(string $name): bool
    {
        $project = $this->findBy(['name' => $name]);

        if (empty($project)) {
            return false;
        }
        return true;
    }

    public function existsById(int $id): bool
    {
        $project = $this->findBy(['id' => $id]);

        if (empty($project)) {
            return false;
        }
        return true;
    }

    public function getById(int $id): Project|null
    {
        return $this->findOneBy(['id' => $id]);
    }


}
