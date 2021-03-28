<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Bot\Action\BotArgumentDtoInterface;
use App\Entity\Message\Message;
use App\Exception\Service\ArgumentParseException;
use App\Exception\TgAppExceptionInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;
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
     * @throws ReflectionException
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

        $dtoClassName = $this->dtoClassNameCreator->create($actionClassName);

        if (!class_exists($dtoClassName) && $argsStr === '') {
            return null;
        }

        if (!class_exists($dtoClassName)) {
            return null;
        }

        $parametersValue = explode(' ', $argsStr);

        $reflection = new ReflectionClass($dtoClassName);
        $parameters = $reflection->getConstructor()->getParameters();
        foreach ($parameters as $i => $parameter) {
            if (isset($parametersValue[$i])) {
                continue;
            }

            if (!$parameter->allowsNull()) {
                throw ArgumentParseException::invalidArgument(
                    $dtoClassName,
                    sprintf('Argument %s can\'t be null', $parameter->name)
                );
            }

            $parametersValue[$i] = null;
        }

        try {
            $dto = new $dtoClassName(...$parametersValue);
        } catch (Throwable $e) {
            throw ArgumentParseException::invalidArgument($dtoClassName, $e->getMessage());
        }

        return $dto;
    }
}
