<?php

declare(strict_types=1);

namespace App\Tests\Webhook;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function file_get_contents;

class TokenActionFunctionalTest extends WebTestCase
{
    public function testTokenReturn200(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/webhook/token',
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/data/valid_bot_command_message.json')
        );

        $response = $client->getResponse();

        self::assertEquals('{"ok":true}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testTokenReturn400(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/webhook/token',
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/data/invalid_bot_command_message.json')
        );

        $response = $client->getResponse();

        self::assertEquals(400, $response->getStatusCode());
    }
}
