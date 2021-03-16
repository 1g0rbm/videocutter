<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Bot\Action\BotArgumentDtoInterface;
use App\Entity\Message\Message;
use App\Exception\TgAppExceptionInterface;
use function class_exists;
use function mb_substr;
use function trim;

class ArgumentParserService
{
    private ActionDtoClassNameCreator $dtoClassNameCreator;

    public function __construct(ActionDtoClassNameCreator $dtoClassNameCreator)
    {
        $this->dtoClassNameCreator = $dtoClassNameCreator;
    }

    /**
     * @param Message $message
     * @param string  $actionClassName
     *
     * @return BotArgumentDtoInterface|null
     * @throws TgAppExceptionInterface
     */
    public function parse(Message $message, string $actionClassName): ?BotArgumentDtoInterface
    {
        $text = $message->getText();

        $argsStr = '';
        if (!empty($message->getEntities())) {
            foreach ($message->getEntities() as $entity) {
                if ($entity->getType() !== 'bot_command') {
                    continue;
                }

                $argsStr = trim(mb_substr($text, $entity->getOffset() + $entity->getLength()));
            }
        } else {
            $argsStr = trim($message->getText());
        }

        if ($argsStr === '') {
            return null;
        }

        $dtoClassName = $this->dtoClassNameCreator->create($actionClassName);

        if (!class_exists($dtoClassName)) {
            return null;
        }

        return new $dtoClassName(explode(' ', $argsStr));
    }
}
