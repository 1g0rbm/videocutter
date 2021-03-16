<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\BotAction\Action\CommandNotFound;
use App\Exception\Service\IncorrectActionClassException;
use App\Exception\TgAppExceptionInterface;
use App\Service\ActionDtoClassNameCreator;
use PHPUnit\Framework\TestCase;
use function get_class;

class ActionDtoClassNameCreatorTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     */
    public function testCreateSuccess(): void
    {
        $service = new ActionDtoClassNameCreator();

        $command = new CommandNotFound();

        self::assertEquals(
            'App\Dto\Bot\Action\NotFoundDto',
            $service->create(get_class($command))
        );
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testCreateThrowIncorrectActionException(): void
    {
        $service = new ActionDtoClassNameCreator();

        self::expectException(IncorrectActionClassException::class);

        $service->create('wrong_class');
    }
}
