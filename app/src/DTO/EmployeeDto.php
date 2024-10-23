<?php

namespace App\DTO;

use App\Validator\EntityExists;
use App\Validator\EntityUniqueField;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

final class EmployeeDto
{
    #[Assert\Type(type: Types::INTEGER, groups: ['update', 'patch'])]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[EntityExists(
        entityClass: 'App\Entity\Employee',
        field: 'id',
        message: 'Employee with ID {{ value }} does not exist.',
        groups: ['update', 'patch']
    )]
    public ?int $id = null;

    #[Assert\Type(type: Types::STRING, groups: ['update', 'patch'])]
    #[Assert\Choice(choices: ['working', 'dismissal'], groups: ['update', 'patch'], match: true)]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[Assert\Length(min: 3, max: 20, groups: ['update', 'patch'])]
    public ?string $status = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Employee',
        field: 'fullName',
        message: 'Employee with {{ field }} {{ value }} already exists.',
        groups: ['create', 'update']
    )]
    #[Assert\Length(min: 6, max: 30, groups: ['create', 'update'])]
    public ?string $fullName = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Choice(
        choices: ['programmer','administrator','devops','designer'],
        groups: ['create', 'update'],
        match: true
    )]
    #[Assert\Length(min: 4, max: 25, groups: ['create', 'update'])]
    public ?string $position = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Email(groups: ['create', 'update'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Employee',
        field: 'email',
        message: 'Employee with {{ field }} {{ value }} already exists.',
        groups: ['create', 'update']
    )]
    #[Assert\Length(min: 6, max: 20, groups: ['create', 'update'])]
    public ?string $email = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Length(min: 6, max: 12, groups: ['create', 'update'])]
    public ?string $phoneNumber = null;

    #[Assert\DateTime('Y-m-d')]
    #[Assert\NotNull(groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\LessThan('today', groups: ['create', 'update'])]
    #[Assert\GreaterThan(value: '1950-01-01', groups: ['create', 'update'])]
    public ?DateTimeImmutable $dateOfBrith = null;

    //#[Assert\NotNull(groups: ['create', 'update'])]
    //#[Assert\Date(groups: ['create', 'update'])]
    //#[Assert\NotBlank(groups: ['create', 'update'])]
    //#[Assert\LessThan(value: new DateTimeImmutable(), groups: ['create', 'update'])]
    //#[Assert\GreaterThan(value: '1950-01-01', groups: ['create', 'update'])]
    //public ?string $dateOfBrith = null;


}