<?php

declare(strict_types=1);

namespace App\Exception;

class TgWebhookUnauthorizedAbstractException extends TgProblemAbstractException
{
    public static function tokenNotFound(): TgAppExceptionInterface
    {
        return self::create('auth', 'Token not found in webhook', 401);
    }

    public static function wrongToken(string $token): TgAppExceptionInterface
    {
        return self::create('auth', sprintf('Send wrong token %s', $token), 401);
    }
}
