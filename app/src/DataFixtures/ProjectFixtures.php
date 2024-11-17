<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $project = new Project();

        $project->setName('Marketplace');
        $project->setClient('Digital Commerce');

        $manager->persist($project);

        $manager->flush();

        $this->addReference('project', $project);
    }
}