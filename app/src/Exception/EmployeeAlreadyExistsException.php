<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeAlreadyExistsException extends HttpException
{
    public function __construct()
    {
        parent::__construct(422, 'Employee with given name already exists');
    }
}