<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FunctionalEmployeeControllerTestFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $employee = new Employee();

        $employee->setFullName('Ivan Ivanov');
        $employee->setEmail('ivanivan@gmail.com');
        $employee->setPosition('programmer');
        $employee->setPhoneNumber('79999999');
        $employee->setDateOfBrith(new DateTimeImmutable('1970-10-10'));


        $project = new Project();

        $project->setName('Crypto Exchange');
        $project->setClient('TokenSwap');

        $manager->persist($project);
        $manager->flush();


        $employee->addProject($project);

        $manager->persist($employee);
        $manager->flush();

        $projectWithoutEmployees = new Project();

        $projectWithoutEmployees->setName('Online store');
        $projectWithoutEmployees->setClient('QWERTY');

        $manager->persist($projectWithoutEmployees);
        $manager->flush();

        $this->addReference('projectWithoutEmployees', $projectWithoutEmployees);

        $this->addReference('projectWithEmployee', $project);

        $this->addReference('employee', $employee);
    }
}