<?php

declare(strict_types=1);

namespace App\BotAction\Action;

use App\BotAction\BotActionInterface;
use App\Entity\Message\Data;

class CommandStart implements BotActionInterface
{
    public const NAME = '/start';

    public function getCommand(): string
    {
        return self::NAME;
    }

    public function run(Data $data)
    {

    }
}
