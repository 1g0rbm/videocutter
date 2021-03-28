<?php

declare(strict_types=1);

namespace App\Dto\Bot\Action;

use Webmozart\Assert\Assert;

class NotFoundDto implements BotArgumentDtoInterface
{
    private string $arg1;

    private ?string $arg2;

    public function __construct(string $arg1, ?string $arg2)
    {
        Assert::stringNotEmpty($arg1);

        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }

    public function getArg1(): ?string
    {
        return $this->arg1;
    }

    public function getArg2(): ?string
    {
        return $this->arg2;
    }
}
