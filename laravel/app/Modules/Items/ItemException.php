<?php

namespace App\Modules\Items;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ItemException extends HttpException
{
    public function __construct(int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
    }
}
