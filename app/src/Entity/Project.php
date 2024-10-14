<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'name', unique: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, mappedBy: 'projects')]
    private Collection $employees;

    #[ORM\Column(name: 'client')]
    private ?string $client = null;


    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getEmployees(): ArrayCollection|Collection
    {
        return $this->employees;
    }


    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }


    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): void
    {
        $this->client = $client;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
}
