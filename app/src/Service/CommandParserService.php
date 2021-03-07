<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BotCommand;
use App\Entity\Message\Message;
use function mb_substr;

class CommandParserService
{
    public function parse(Message $message): BotCommand
    {
        $text = $message->getText();

        $commandStructure = [];
        foreach ($message->getEntities() as $entity) {
            $commandStructure[$entity->getType()] = mb_substr($text, $entity->getOffset(), $entity->getLength());
        }

        return BotCommand::create($commandStructure);
    }
}
