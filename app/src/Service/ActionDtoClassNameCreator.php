<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\Service\IncorrectActionClassException;
use App\Exception\TgAppExceptionInterface;
use function sprintf;
use function str_replace;
use function str_starts_with;

class ActionDtoClassNameCreator
{
    private const ACTION_NAMESPACE_PATTERN = 'App\BotAction\Action\Command';

    private const DTO_NAMESPACE_PATTERN = 'App\Dto\Bot\Action\%sDto';

    /**
     * @param string $actionClassName
     *
     * @return string
     * @throws TgAppExceptionInterface
     */
    public function create(string $actionClassName): string
    {
        if (!str_starts_with($actionClassName, self::ACTION_NAMESPACE_PATTERN)) {
            throw IncorrectActionClassException::byClass($actionClassName);
        }

        return sprintf(
            self::DTO_NAMESPACE_PATTERN,
            str_replace(self::ACTION_NAMESPACE_PATTERN, '', $actionClassName)
        );
    }
}
