<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectAlreadyHasThisEmployeeException extends HttpException
{
    public function __construct()
    {
        parent::__construct(422, 'The project already has this employee');
    }
}