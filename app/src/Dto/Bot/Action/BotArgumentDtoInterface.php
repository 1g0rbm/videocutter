<?php

declare(strict_types=1);

namespace App\Dto\Bot\Action;

interface BotArgumentDtoInterface
{
    public function __construct(array $arguments);
}
