<?php

declare(strict_types=1);

namespace App\Entity;

use Webmozart\Assert\Assert;

class BotCommand
{
    private string $command;

    public static function create(array $structure): self
    {
        Assert::keyExists($structure, 'bot_command');

        $obj = new self();

        $obj->command = $structure['bot_command'];

        return $obj;
    }

    public function getCommand(): string
    {
        return $this->command;
    }
}
