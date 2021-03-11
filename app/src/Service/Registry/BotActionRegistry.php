<?php

declare(strict_types=1);

namespace App\Service\Registry;

use App\BotAction\BotActionInterface;
use App\Exception\BotAction\ActionNotFoundException;
use App\Exception\BotAction\BotActionRegistryException;
use App\Exception\TgAppExceptionInterface;

class BotActionRegistry
{
    /**
     * @var BotActionInterface[]
     */
    private array $actions = [];

    /**
     * @param string $botCommand
     *
     * @return BotActionInterface
     *
     * @throws TgAppExceptionInterface
     */
    public function get(string $botCommand): BotActionInterface
    {
        if (!isset($this->actions[$botCommand])) {
            return $this->getDefaultCommand();
        }

        return $this->actions[$botCommand];
    }

    /**
     * @return BotActionInterface
     * @throws TgAppExceptionInterface
     */
    public function getDefaultCommand(): BotActionInterface
    {
        if (!isset($this->actions['/no_command'])) {
            throw ActionNotFoundException::defaultCommand();
        }

        return $this->actions['/no_command'];
    }

    /**
     * @param BotActionInterface $action
     *
     * @throws TgAppExceptionInterface
     */
    public function addAction(BotActionInterface $action): void
    {
        if (isset($this->actions[$action->getCommand()])) {
            throw BotActionRegistryException::actionAlreadyExist($action->getCommand());
        }

        $this->actions[$action->getCommand()] = $action;
    }
}
