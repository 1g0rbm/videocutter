<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

interface TgAppExceptionInterface extends Throwable
{
    public static function create(string $label, string $message, int $code): self;

    public function getTelegramProblem(): TelegramProblem;
}
