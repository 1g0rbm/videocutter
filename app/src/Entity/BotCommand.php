<?php

declare(strict_types=1);

namespace App\Entity;

use Webmozart\Assert\Assert;

class BotCommand
{
    private string $command;

    private string $text;

    public static function create(array $structure): self
    {
        Assert::keyExists($structure, 'text');

        $obj = new self();

        $obj->command = $structure['command'] ?? '/no_command';
        $obj->text    = $structure['text'];

        return $obj;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
