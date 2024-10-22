<?php

namespace App\EntityListener;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;


#[AsEntityListener(event: Events::prePersist, entity: Project::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Project::class)]
readonly class ProjectEntityListener
{
    public function __construct(
        private SluggerInterface $slugger
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
}