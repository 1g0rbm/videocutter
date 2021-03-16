<?php

declare(strict_types=1);

namespace App\Exception\Service;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgProblemAbstractException;
use function sprintf;

class IncorrectActionClassException extends TgProblemAbstractException
{
    public static function byClass(string $class): TgAppExceptionInterface
    {
        return self::create(
            'dto-classname-define',
            sprintf('Incorrect classname "%s"', $class),
            500
        );
    }
}
