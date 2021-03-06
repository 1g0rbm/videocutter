<?php

namespace App\Exception\Dto\Request;

use App\Exception\TelegramProblem;
use App\Exception\TgAppExceptionInterface;
use InvalidArgumentException;
use function sprintf;
use function mb_strtoupper;

class WebhookRequestException extends InvalidArgumentException implements TgAppExceptionInterface
{
    private TelegramProblem $problem;

    public static function create(string $label, string $message, int $code): self
    {
        $e          = new self(sprintf('[%s] %s', mb_strtoupper($label), $message));
        $e->problem = new TelegramProblem($e->getMessage(), $code);

        return $e;
    }

    public function getTelegramProblem(): TelegramProblem
    {
        return $this->problem;
    }
}
