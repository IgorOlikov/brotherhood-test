<?php

namespace App\EntityListener;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Employee::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Employee::class)]
class EmployeeEntityListener
{
    public function __construct(
        private SluggerInterface $slugger
    )
    {
    }

    public function prePersist(Employee $employee, LifecycleEventArgs $event): void
    {
        $employee->computeSlug($this->slugger);
    }

    public function preUpdate(Employee $employee, LifecycleEventArgs $event): void
    {
        $employee->computeSlug($this->slugger);
    }
}