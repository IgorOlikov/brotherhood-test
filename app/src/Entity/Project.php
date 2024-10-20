<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class), ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['name', 'slug'], message: 'Project with this name already exists')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'name',length: 200, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $client;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $status;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $slug;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $openedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $closedAt = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, mappedBy: 'projects')]
    private Collection $employees;


    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->openedAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setSlug(SluggerInterface $slugger): void
    {
        $this->slug = $slugger->slug($this->getName());
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


}
