<?php

namespace App\Modules\Comments;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentException extends HttpException
{
    public function __construct(int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
    }
}
