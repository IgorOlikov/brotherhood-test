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

    //#[Assert\NotBlank(groups: ['update', 'patch'])]
    //#[Assert\Type(type: Types::INTEGER, groups: ['update', 'patch'])]
    //#[EntityExists(
    //    entityClass: 'App\Entity\Project',
    //    field: 'id',
    //    message: 'Project with ID {{ value }} does not exist.',
    //    groups: ['update', 'patch']
    //)]
    //public ?int $id = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\Length(min: 5, max: 15, groups: ['create', 'update'])]
    #[EntityUniqueField(
        entityClass: 'App\Entity\Project',
        field: 'name',
        message: 'Project with {{ field }} {{ value }} already exists.',
        groups: ['update', 'patch', 'create']
    )]
    public ?string $name = null;

    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Type(type: Types::STRING, groups: ['create', 'update'])]
    #[Assert\Length(min: 5, max: 15, groups: ['create', 'update'])]
    public ?string $client = null;

    #[Assert\NotBlank(groups: ['update'])]
    #[Assert\Type(type: Types::STRING, groups: ['update', 'patch'])]
    #[Assert\Choice(choices: ['opened', 'closed'], groups: ['update', 'patch'], match: true)]
    public ?string $status = null;


}