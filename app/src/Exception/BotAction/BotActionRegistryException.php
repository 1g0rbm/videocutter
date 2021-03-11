<?php

declare(strict_types=1);

namespace App\Exception\BotAction;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgProblemAbstractException;
use function sprintf;

class BotActionRegistryException extends TgProblemAbstractException
{
    public static function actionAlreadyExist(string $class): TgAppExceptionInterface
    {
        return self::create(
            'action_registry',
            sprintf('Registry already contain action "%s"', $class),
            500
        );
    }
}
