<?php

namespace App\Modules\ItemsClaimedNotifications;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ItemsClaimedNotificationException extends HttpException
{
    public function __construct(int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
    }
}
