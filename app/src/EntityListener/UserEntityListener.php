<?php

namespace App\EntityListener;



use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Redis;

#[AsEntityListener(event: Events::prePersist, entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, entity: User::class)]
#[AsEntityListener(event: Events::postRemove, entity: User::class)]
readonly class UserEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
        private Redis            $redis
    )
    {
    }

    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        $user->computeSlug($this->slugger);
    }

    public function preUpdate(User $user, LifecycleEventArgs $event): void
    {
        $user->computeSlug($this->slugger);
    }

    public function postRemove(User $user, LifecycleEventArgs $eventArgs): void
    {
        $slug = $user->getSlug();

        if ($this->redis->exists("User:slug:{$slug}")) {
            $this->redis->del("User:slug:{$slug}");
        }

    }
}