<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\BotAction\Action\CommandNotFound;
use App\BotAction\Action\CommandStart;
use App\Dto\Bot\Action\BotArgumentDtoInterface;
use App\Dto\Bot\Action\NotFoundDto;
use App\Entity\Message\Data;
use App\Exception\TgAppExceptionInterface;
use App\Service\ActionDtoClassNameCreator;
use App\Service\ArgumentParserService;
use Exception;
use PHPUnit\Framework\TestCase;

class ArgumentParserServiceTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     * @throws Exception
     */
    public function testParseSuccess(): void
    {
        $service = new ArgumentParserService(new ActionDtoClassNameCreator());

        $data    = Data::createFromArray(self::getWebhookCommand());
        $command = new CommandNotFound();

        /** @var NotFoundDto $dto */
        $dto = $service->parse($data->getMessage(), get_class($command));

        self::assertInstanceOf(BotArgumentDtoInterface::class, $dto);
        self::assertEquals('arg1', $dto->getArg1());
        self::assertEquals('arg2', $dto->getArg2());
    }

    /**
     * @throws TgAppExceptionInterface
     * @throws Exception
     */
    public function testParseReturnNullIfThereAreNotArguments(): void
    {
        $service = new ArgumentParserService(new ActionDtoClassNameCreator());

        $data    = Data::createFromArray(self::getWebhookCommandWithoutArguments());
        $command = new CommandNotFound();

        self::assertNull($service->parse($data->getMessage(), get_class($command)));
    }

    /**
     * @throws TgAppExceptionInterface
     * @throws Exception
     */
    public function testParseReturnNullIfThereAreNotDtoClass(): void
    {
        $service = new ArgumentParserService(new ActionDtoClassNameCreator());

        $data    = Data::createFromArray(self::getWebhookCommand());
        $command = new CommandStart();

        self::assertNull($service->parse($data->getMessage(), get_class($command)));
    }

    private static function getWebhookCommandWithoutArguments(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_command_message.json'),
            true
        );
    }

    private static function getWebhookCommand(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_command_with_arguments_message.json'),
            true
        );
    }
}
