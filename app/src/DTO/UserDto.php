<?php

namespace App\DTO;

use App\DTO\Interface\DtoInterface;
use App\DTO\Trait\HydratePatchTrait;
use App\Entity\User;
use App\Validator\EntityUniqueField;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto implements DtoInterface
{
    use HydratePatchTrait;


    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 8, max: 20, groups: ['create'])]
    #[EntityUniqueField(
        entityClass: User::class,
        field: 'name',
        message: 'Project with {{ field }} {{ value }} already exists.',
        groups: ['create']
    )]
    public ?string $name = null;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Email(groups: ['create'])]
    #[Assert\Length(min: 5, max: 30, groups: ['create'])]
    #[EntityUniqueField(
        entityClass: User::class,
        field: 'email',
        message: 'Project with {{ field }} {{ value }} already exists.',
        groups: ['create']
    )]
    public ?string $email = null;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 8, max: 20, groups: ['create'])]
    public ?string $password = null;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\IdenticalTo(propertyPath: 'password', groups: ['create'])]
    public ?string $passwordConfirmation = null;




}