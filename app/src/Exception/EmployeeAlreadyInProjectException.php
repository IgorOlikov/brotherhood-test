<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeAlreadyInProjectException extends HttpException
{
    public function __construct()
    {
        parent::__construct(422, 'Employee already in this project');
    }
}