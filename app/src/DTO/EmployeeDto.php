<?php

namespace App\DTO;

use App\DTO\Interface\DtoInterface;
use App\DTO\Trait\HydratePatchTrait;
use App\Validator\EntityUniqueField;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

final class EmployeeDto implements DtoInterface
{
    use HydratePatchTrait;

    #[Assert\NotBlank(groups: ['update'])]
    #[Assert\Type(type: Types::STRING, groups: ['update', 'patch'])]
    #[Assert\Choice(choices: ['working', 'dismissal'], groups: ['update', 'patch'], match: true)]
    #[Assert\Length(min: 3, max: 20, groups: ['update', 'patch'])]
    public ?string $status = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Type(type: Types::STRING, groups: ['create', 'update', 'patch'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Employee',
        field: 'fullName',
        message: 'Employee with {{ field }} {{ value }} already exists.',
        groups: ['create', 'update', 'patch']
    )]
    #[Assert\Length(min: 6, max: 30, groups: ['create', 'update', 'patch'])]
    public ?string $fullName = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Type(type: Types::STRING, groups: ['create', 'update', 'patch'])]
    #[Assert\Choice(
        choices: ['programmer','administrator','devops','designer'],
        groups: ['create', 'update', 'patch'],
        match: true
    )]
    #[Assert\Length(min: 4, max: 25, groups: ['create', 'update', 'patch'])]
    public ?string $position = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Type(type: Types::STRING, groups: ['create', 'update', 'patch'])]
    #[Assert\Email(groups: ['create', 'update', 'patch'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Employee',
        field: 'email',
        message: 'Employee with {{ field }} {{ value }} already exists.',
        groups: ['create', 'update', 'patch']
    )]
    #[Assert\Length(min: 6, max: 20, groups: ['create', 'update', 'patch'])]
    public ?string $email = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Type(type: Types::STRING, groups: ['create', 'update', 'patch'])]
    #[Assert\Length(min: 6, max: 12, groups: ['create', 'update', 'patch'])]
    public ?string $phoneNumber = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    //#[Assert\DateTime(format: 'Y-m-d')] // ,groups: ['create', 'update', 'patch']
    #[Assert\LessThan('today', groups: ['create', 'update', 'patch'])]
    #[Assert\GreaterThan(value: '1950-01-01', groups: ['create', 'update', 'patch'])]
    public ?DateTimeImmutable $dateOfBrith = null;


}