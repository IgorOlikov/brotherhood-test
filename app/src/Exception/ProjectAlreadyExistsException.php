<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectAlreadyExistsException extends HttpException
{
    public function __construct()
    {
        parent::__construct(422, 'Project with given name already exists');
    }
}