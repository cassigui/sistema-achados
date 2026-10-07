<?php

namespace App\Modules\Users;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserException extends HttpException
{
    public function __construct(int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
    }
}
