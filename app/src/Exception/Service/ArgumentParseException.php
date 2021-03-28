<?php

declare(strict_types=1);

namespace App\Exception\Service;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgProblemAbstractException;

class ArgumentParseException extends TgProblemAbstractException
{
    public static function invalidArgument(string $dtoClass, string $message): TgAppExceptionInterface
    {
        return self::create(
            'argument-parser',
            sprintf('Dto "%s" threw exception with message: "%s"', $dtoClass, $message),
            200
        );
    }
}
