<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\BotCommand;
use App\Entity\Message\Data;
use App\Entity\Message\Message;
use App\Service\CommandParserService;
use Exception;
use PHPUnit\Framework\TestCase;
use function json_decode;
use function file_get_contents;

class CommandParseServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testParseSuccess(): void
    {
        $service = new CommandParserService();

        $data    = Data::createFromArray(self::getWebhookData());
        $command = $service->parse($data->getMessage());

        self::assertInstanceOf(BotCommand::class, $command);
        self::assertEquals('/start', $command->getCommand());
    }

    private static function getWebhookData(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_command_message.json'),
            true
        );
    }
}
