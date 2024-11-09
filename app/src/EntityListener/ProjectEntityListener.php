<?php

namespace App\EntityListener;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Redis;


#[AsEntityListener(event: Events::prePersist, entity: Project::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Project::class)]
#[AsEntityListener(event: Events::postPersist, entity: Project::class)]
readonly class ProjectEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
        private Redis $redis,
        private SerializerInterface $serializer
    )
    {
    }

    public function prePersist(Project $project, LifecycleEventArgs $event): void
    {
        $project->computeSlug($this->slugger);
    }

    public function preUpdate(Project $project, LifecycleEventArgs $event): void
    {
        $project->computeSlug($this->slugger);
    }

    public function postPersist(Project $project, LifecycleEventArgs $event) :void
    {
        $this->redis->set("Project:slug:{$project->getSlug()}", $this->serializer->serialize(
                data: $project,
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