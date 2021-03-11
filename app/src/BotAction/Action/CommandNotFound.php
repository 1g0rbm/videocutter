<?php

declare(strict_types=1);

namespace App\BotAction\Action;

use App\BotAction\BotActionInterface;
use App\Entity\Message\Data;
use App\Entity\Message\Response;

class CommandNotFound implements BotActionInterface
{
    public const NAME = '/no_command';

    public function getCommand(): string
    {
        return self::NAME;
    }

    public function run(Data $data): Response
    {
        return new Response(
            $data->getMessage()->getChat()->getId(),
            'response text'
        );
    }
}
