<?php

declare(strict_types=1);

namespace App\Tests\Service\TgBot;

use App\Entity\Message;
use App\Service\TgBot\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\RequestException;
use function getenv;
use function http_build_query;
use function sprintf;

class ApiTest extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testSendMessageSuccess(): void
    {
        $historyContainer = [];
        $api              = new Api(
            $this->createClient(
                $status = 200,
                $body = 'content',
                $historyContainer
            ),
            $tgToken = getenv('TG_TOKEN')
        );

        $response = $api->sendMessage(new Message\Response(1, 'response text'));

        self::assertEquals($body, $response);

        /** @var Request $request */
        $request = $historyContainer[0]['request'];

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals(sprintf('/bot%s/sendMessage', $tgToken), $request->getUri());
        self::assertEquals(
            http_build_query(
                [
                    'chat_id' => 1,
                    'text' => 'response text',
                    'parse_mode' => 'markdown',
                    'reply_markup' => json_encode([]),
                ]
            ),
            $request->getBody()->getContents()
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testSendMessageError(): void
    {
        $historyContainer = [];
        $api              = new Api(
            $this->createErrorClient($historyContainer),
            $tgToken = getenv('TG_TOKEN')
        );

        self::expectException(RequestException::class);

        $api->sendMessage(new Message\Response(1, 'response text'));
    }

    private function createErrorClient(array &$container): Client
    {
        $handlerStack = HandlerStack::create(
            new MockHandler(
                [
                    new RequestException('Error message', new Request('POST', 'test')),
                ]
            )
        );
        $handlerStack->push(Middleware::history($container));

        return new Client(['handler' => $handlerStack]);
    }

    private function createClient(int $status, string $body, array &$container): Client
    {
        $handlerStack = HandlerStack::create(new MockHandler([new Response($status, [], $body)]));
        $handlerStack->push(Middleware::history($container));

        return new Client(['handler' => $handlerStack]);
    }
}
