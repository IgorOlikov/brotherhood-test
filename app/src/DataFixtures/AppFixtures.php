<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
         $project = new Project();

         $project->setName('nametest');
         $project->setClient('clienttest');

         $manager->persist($project);

         $manager->flush();

         $this->addReference('project', $project);
    }
}
