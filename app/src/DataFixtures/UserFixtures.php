<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture implements FixtureInterface
{

    private readonly Generator $generator;

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->generator = Factory::create();

    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setName('user');
        $user->setEmail('user@example.com');

        $hashedPassword = $this->passwordHasher->hashPassword($user, $this->generator->password);

        $user->setPassword($hashedPassword);

        //$user->setEmailVerifiedAt();

        $manager->persist($user);

        $manager->flush();

        $this->addReference('user', $user);
    }
}