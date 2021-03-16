<?php

declare(strict_types=1);

namespace App\Dto\Bot\Action;

class NotFoundDto implements BotArgumentDtoInterface
{
    private string $arg1;

    private string $arg2;

    public function __construct(array $arguments)
    {
        $this->arg1 = $arguments[0];
        $this->arg2 = $arguments[1];
    }

    public function getArg1(): string
    {
        return $this->arg1;
    }

    public function getArg2(): string
    {
        return $this->arg2;
    }
}
