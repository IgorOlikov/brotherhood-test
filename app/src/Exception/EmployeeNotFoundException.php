<?php

namespace App\Exception;



use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeNotFoundException extends HttpException
{

    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($statusCode, $message);
    }
}