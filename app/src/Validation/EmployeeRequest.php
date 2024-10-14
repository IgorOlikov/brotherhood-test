<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeRequest
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(min: 10, max: 30)]
    private ?string $fullName = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['программист','администратор','devops','дизайнер'], match: true)]
    private ?string $position = null;

    #[Assert\Email]
    private ?string $email = null;

    #[Assert\Type('string')]
    #[Assert\Length(min: 6, max: 12)]
    private ?string $phoneNumber = null;

    #[Assert\Type('integer')]
    #[Assert\Range(min: 18, max: 65)]
    private ?int $age = null;

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param string|null $fullName
     */
    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @param int|null $age
     */
    public function setAge(?int $age): void
    {
        $this->age = $age;
    }







}