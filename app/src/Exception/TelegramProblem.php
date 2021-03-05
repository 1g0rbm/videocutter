<?php

declare(strict_types=1);

namespace App\Exception;

class TelegramProblem
{
    private string $message;

    private int $httpStatusCode;

    public function __construct(string $message, $httpStatusCode)
    {
        $this->message        = $message;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
