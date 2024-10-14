<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeNotInProjectException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Employee not in this project');
    }
}