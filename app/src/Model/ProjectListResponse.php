<?php

namespace App\Model;

class ProjectListResponse
{
    public function __construct(
        private readonly array $projects
    )
    {
    }

    /**
     * @return array
     */
    public function getProjects(): array
    {
        return $this->projects;
    }


}