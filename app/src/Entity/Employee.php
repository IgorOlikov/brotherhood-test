<?php

namespace App\Entity;

use App\Entity\Interface\EntityInterface;
use App\Repository\EmployeeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;


#[ORM\Index(name: 'employee_slug_idx', columns: ['slug'])]
#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[UniqueEntity(fields: ['email', 'fullName'], message: 'Employee with this name, email or phone number already exists')]
class Employee implements EntityInterface
{
    #[Groups(groups: ['public'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, nullable: false, options: ['default' => 'working'])]
    private string $status = 'working';

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 200)]
    private string $fullName;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $position;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $slug;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $email;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::STRING, length: 12, unique: true)]
    private string $phoneNumber;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $dateOfBrith;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $dateOfEmployment;

    #[Groups(groups: ['public'])]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ["default" => null])]
    private ?DateTimeImmutable $dateOfDismissal = null;

    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'employees')]
    #[ORM\JoinTable(name: 'employees_projects')]
    private Collection $projects;


    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->dateOfEmployment = new DateTimeImmutable();
    }


    public function computeSlug(SluggerInterface $slugger): void
    {
        $this->slug = $slugger->slug($this->fullName)->lower();
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


    public function getDateOfDismissal(): ?DateTimeImmutable
    {
        return $this->dateOfDismissal;
    }

    public function setDateOfDismissal(DateTimeImmutable $dateOfDismissal): void
    {
        $this->dateOfDismissal = $dateOfDismissal;
    }

    public function removeProject(Project $project): void
    {
        $this->projects->removeElement($project);
    }


}
