<?php

namespace App\DTO;


use App\DTO\Interface\DtoInterface;
use App\DTO\Trait\HydratePatchTrait;
use App\Validator\EntityExists;
use App\Validator\EntityUniqueField;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

final class ProjectDto implements DtoInterface
{
    use HydratePatchTrait;

    #[Assert\Type(type: Types::INTEGER, groups: ['update', 'patch'])]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[EntityExists(
        entityClass: 'App\Entity\Project',
        field: 'id',
        message: 'Project with ID {{ value }} does not exist.',
        groups: ['update', 'patch']
    )]
    public ?int $id = null;

    #[Assert\Type(type: Types::STRING, groups: ['create'])]
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 5, max: 15, groups: ['create'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Project',
        field: 'name',
        message: 'Project with {{ field }} {{ value }} already exists.',
        groups: ['update', 'patch', 'create']
    )]
    public ?string $name = null;

    #[Assert\Type(type: Types::STRING, groups: ['create'])]
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 5, max: 15, groups: ['create'])]
    public ?string $client = null;

    #[Assert\Type(type: Types::STRING, groups: ['update', 'patch'])]
    #[Assert\NotBlank(groups: ['update', 'patch'])]
    #[Assert\Choice(choices: ['opened', 'closed'], groups: ['update', 'patch'], match: true)]
    public ?string $status = null;


}