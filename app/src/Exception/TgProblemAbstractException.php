<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

abstract class TgProblemAbstractException extends Exception implements TgAppExceptionInterface
{
    private TelegramProblem $problem;

    public static function create(string $label, string $message, int $code): self
    {
        $e          = new static(sprintf('[%s] %s', mb_strtoupper($label), $message), $code);
        $e->problem = new TelegramProblem($e->getMessage(), $code);

        return $e;
    }

    public function getTelegramProblem(): TelegramProblem
    {
        return $this->problem;
    }
}
