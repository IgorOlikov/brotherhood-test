<?php

namespace App\Model;

class ProjectModel
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $client
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }




}