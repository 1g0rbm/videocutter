<?php

namespace App\Exception\Dto\Request;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgProblemAbstractException;

class WebhookRequestException extends TgProblemAbstractException
{
    public static function wrongContent(string $message): TgAppExceptionInterface
    {
        return static::create('webhook-token', $message, 400);
    }
}
