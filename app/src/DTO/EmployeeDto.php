<?php

namespace App\DTO;

use App\Validator\EntityExists;
use App\Validator\EntityUniqueField;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeDto
{
    #[Assert\Type(type: Types::INTEGER, groups: ['update', 'patch'])]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[EntityExists(
        entityClass: 'App\Entity\Employee',
        field: 'id',
        message: 'Employee with ID {{ value }} does not exist.',
        groups: ['update', 'patch']
    )]
    private ?int $id = null;

    #[Assert\Type(type: Types::STRING, groups: ['update', 'patch'])]
    #[Assert\Choice(choices: ['working', 'dismissal'], groups: ['update', 'patch'], match: true)]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[Assert\Length(min: 3, max: 20)]
    private ?string $status = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Employee',
        field: 'fullName',
        message: 'Employee with {{ field }} {{ value }} already exists.',
        groups: ['create', 'update']
    )]
    #[Assert\Length(min: 6, max: 30)]
    private ?string $fullName = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Choice(
        choices: ['programmer','administrator','devops','designer'],
        groups: ['create', 'update'],
        match: true
    )]
    #[Assert\Length(min: 4, max: 25)]
    private ?string $position = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Email(groups: ['create', 'update'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Employee',
        field: 'email',
        message: 'Employee with {{ field }} {{ value }} already exists.',
        groups: ['create', 'update']
    )]
    #[Assert\Length(min: 6, max: 20)]
    private ?string $email = null;

    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Length(min: 6, max: 12)]
    private ?string $phoneNumber = null;

    #[Assert\Date(groups: ['create', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    private ?DateTimeImmutable $dateOfBrith = null;


}