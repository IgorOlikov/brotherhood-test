<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;


#[ORM\Entity(repositoryClass: EmployeeRepository::class), ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email', 'phoneNumber', 'fullName', 'slug'], message: 'Employee with this email or phone number already exists')]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $status;

    #[ORM\Column(type: Types::STRING, length: 200)]
    private string $fullName;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $position;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $slug;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 12, unique: true)]
    private string $phoneNumber;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $dateOfBrith;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $dateOfEmployment;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ["default" => null])]
    private DateTimeImmutable $dateOfDismissal;

    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'employees')]
    #[ORM\JoinTable(name: 'employees_projects')]
    private Collection $projects;


    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->dateOfEmployment = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setSlug(SluggerInterface $slugger): void
    {
        $this->slug = $slugger->slug($this->getFullName());
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getDateOfBrith(): DateTimeImmutable
    {
        return $this->dateOfBrith;
    }

    public function setDateOfBrith(DateTimeImmutable $dateOfBrith): void
    {
        $this->dateOfBrith = $dateOfBrith;
    }


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): void
    {
        $this->projects->add($project);
    }

    public function getDateOfEmployment(): DateTimeImmutable
    {
        return $this->dateOfEmployment;
    }


    public function getDateOfDismissal(): DateTimeImmutable
    {
        return $this->dateOfDismissal;
    }

    public function setDateOfDismissal(DateTimeImmutable $dateOfDismissal): void
    {
        $this->dateOfDismissal = $dateOfDismissal;
    }



}
