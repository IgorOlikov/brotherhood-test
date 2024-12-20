<?php

namespace App\Entity;

use App\Entity\Interface\EntityInterface;
use App\Repository\ProjectRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;


#[ORM\Index(name: 'project_slug_idx', columns: ['slug'])]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Project with this name already exists')]
class Project implements EntityInterface
{
    #[Groups(groups: ['public'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[Groups(groups: ['public'])]
    #[ORM\Column(name: 'name',length: 200, unique: true)]
    private string $name;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING)]
    private string $client;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 20, options: ['default' => 'opened'])]
    private string $status = 'opened';

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $slug;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $openedAt;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $closedAt = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, mappedBy: 'projects')]
    private Collection $employees;


    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->openedAt = new DateTimeImmutable();
    }



    public function computeSlug(SluggerInterface $slugger): void
    {
        $this->slug = $slugger->slug($this->name)->lower();
    }

    public function getClosedAt(): ?DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?DateTimeImmutable $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->openedAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function setClient(string $client): void
    {
        $this->client = $client;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): void
    {
        $this->employees->add($employee);
    }

    public function removeEmployee(Employee $employee)
    {

    }


}
