<?php

declare(strict_types=1);

namespace App\Exception\BotAction;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgProblemAbstractException;

class ActionNotFoundException extends TgProblemAbstractException
{
    public static function defaultCommand(): TgAppExceptionInterface
    {
        return self::create(
            'action-registry',
            'Registry can not find default action',
            500
        );
    }
}
