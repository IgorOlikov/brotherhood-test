<?php

namespace App\DTO;


use App\Validator\EntityExists;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

final class ProjectDto
{
    ////TODO add groups serialization context

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