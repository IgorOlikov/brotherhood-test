<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{

    public function __construct(
        ManagerRegistry $registry,

    )
    {
        parent::__construct($registry, Employee::class);
    }

    public function existsByName(string $fullName): array
    {
         return $this->findBy(['fullName' => $fullName]);
    }

    public function getById(int $id): Employee|null
    {
        return $this->findOneBy(['id' => $id]);
    }


}
