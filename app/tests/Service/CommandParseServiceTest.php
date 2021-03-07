<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\BotCommand;
use App\Entity\Message\Data;
use App\Service\CommandParserService;
use Exception;
use PHPUnit\Framework\TestCase;
use function file_get_contents;
use function json_decode;

class CommandParseServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testParseSuccess(): void
    {
        $service = new CommandParserService();

        $data    = Data::createFromArray(self::getWebhookCommand());
        $command = $service->parse($data->getMessage());

        self::assertInstanceOf(BotCommand::class, $command);
        self::assertEquals('/start', $command->getCommand());
        self::assertEquals('', $command->getText());
    }

    /**
     * @throws Exception
     */
    public function testParseCommandWithTextSuccess(): void
    {
        $service = new CommandParserService();

        $data    = Data::createFromArray(self::getWebhookCommandWithText());
        $command = $service->parse($data->getMessage());

        self::assertInstanceOf(BotCommand::class, $command);
        self::assertEquals('/cut', $command->getCommand());
        self::assertEquals('test word', $command->getText());
    }

    /**
     * @throws Exception
     */
    public function testParseTextSuccess(): void
    {
        $service = new CommandParserService();

        $data    = Data::createFromArray(self::getWebhookText());
        $command = $service->parse($data->getMessage());

        self::assertInstanceOf(BotCommand::class, $command);
        self::assertEquals('/no_command', $command->getCommand());
        self::assertEquals('test phrase', $command->getText());
    }

    private static function getWebhookText(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_text.json'),
            true
        );
    }

    private static function getWebhookCommandWithText(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_command_with_text.json'),
            true
        );
    }

    private static function getWebhookCommand(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_command_message.json'),
            true
        );
    }
}
