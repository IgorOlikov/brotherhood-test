<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectDoesNotHaveThisEmployeeException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'The project does not have this Employee');
    }
}