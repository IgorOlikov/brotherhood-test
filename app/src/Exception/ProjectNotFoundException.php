<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectNotFoundException extends HttpException
{
    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($statusCode, $message);
    }
}