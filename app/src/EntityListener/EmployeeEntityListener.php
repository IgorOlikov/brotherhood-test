<?php

namespace App\EntityListener;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Redis;

#[AsEntityListener(event: Events::prePersist, entity: Employee::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Employee::class)]
#[AsEntityListener(event: Events::postPersist, entity: Employee::class)]
class EmployeeEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
        private Redis $redis,
        private SerializerInterface $serializer
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

    public function postPersist(Employee $employee, LifecycleEventArgs $event) :void
    {
        $this->redis->set("Employee:slug:{$employee->getSlug()}", $this->serializer->serialize(
                data: $employee,
                format: 'json',
                context: [
                    DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
                    AbstractNormalizer::GROUPS => ['public'],
                    AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => false,
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => false
                ]
            )
        );
    }
}