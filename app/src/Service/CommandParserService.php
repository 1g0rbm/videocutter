<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BotCommand;
use App\Entity\Message\Message;
use function mb_substr;
use function trim;

class CommandParserService
{
    public function parse(Message $message): BotCommand
    {
        $text = $message->getText();

        $commandStructure = [];
        if (!empty($message->getEntities())) {
            foreach ($message->getEntities() as $entity) {
                if ($entity->getType() !== 'bot_command') {
                    continue;
                }

                $commandStructure['command'] = mb_substr($text, $entity->getOffset(), $entity->getLength());
                $commandStructure['text']    = trim(mb_substr($text, $entity->getOffset() + $entity->getLength()));
            }
        } else {
            $commandStructure['text'] = $message->getText();
        }

        return BotCommand::create($commandStructure);
    }
}
