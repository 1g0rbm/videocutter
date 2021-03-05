<?php

namespace App\Exception\Dto\Request;

use App\Exception\TelegramProblem;
use App\Exception\TgAppExceptionInterface;
use InvalidArgumentException;

class WebhookRequestException extends InvalidArgumentException implements TgAppExceptionInterface
{
    private TelegramProblem $problem;

    public static function create(string $message, int $code): self
    {
        $e          = new self($message);
        $e->problem = new TelegramProblem($message, $code);

        return $e;
    }

    public function getTelegramProblem(): TelegramProblem
    {
        return $this->problem;
    }
}
