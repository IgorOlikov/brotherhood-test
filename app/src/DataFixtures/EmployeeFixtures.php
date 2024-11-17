<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $employee = new Employee();

        $employee->setFullName('Ivan Ivanov');
        $employee->setEmail('ivanivan@gmail.com');
        $employee->setPosition('programmer');
        $employee->setPhoneNumber('79999999');
        $employee->setDateOfBrith(new DateTimeImmutable('1970-10-10'));

        $manager->persist($employee);

        $manager->flush();

        $this->addReference('employee', $employee);
    }
}