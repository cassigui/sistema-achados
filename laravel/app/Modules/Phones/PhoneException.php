<?php

namespace App\Modules\Phones;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PhoneException extends HttpException
{
    public function __construct(int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
    }
}
