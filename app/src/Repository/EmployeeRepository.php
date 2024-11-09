<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Redis;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private Redis $redis
    )
    {
        parent::__construct($registry, Employee::class);
    }

    public function employeeHasProjectById($employeeId, $projectId): bool
    {
        $sql = 'select * from employees_projects ep where ep.employee_id = :employeeId and ep.project_id = :projectId';

        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                $sql,
                ['employeeId' => $employeeId, 'projectId' => $projectId],
                ['employeeId' => Types::INTEGER, 'projectId' => Types::INTEGER]
            );

        $result = $query->fetchAssociative();

        if (!empty($result)) {
            return true;
        }
        return false;
    }

    public function findOneByFromRedis(array $criteria): ?string
    {
        $colName = array_key_first($criteria) ?? 'slug';

        $project = $this->redis->get("Employee:{$colName}:{$criteria[$colName]}");

        if ($project !== false) {
            return $project;
        }

        return null;
    }


    //    /**
    //     * @return Employee[] Returns an array of Employee objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Employee
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
