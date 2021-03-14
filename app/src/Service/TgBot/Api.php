<?php

declare(strict_types=1);

namespace App\Service\TgBot;

use App\Entity\Message\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Api implements TgSendMessageInterface
{
    private Client $client;

    private string $token;

    public function __construct(Client $client, string $token)
    {
        $this->client = $client;
        $this->token  = $token;
    }

    /**
     * @param Response $response
     *
     * @return string
     * @throws GuzzleException
     */
    public function sendMessage(Response $response): string
    {
        return $this->send(
            'POST',
            'sendMessage',
            $response->toArray()
        );
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    public function getMe(): string
    {
        return $this->send(
            'POST',
            'getMe',
            []
        );
    }

    /**
     * @param string $httpMethod
     * @param string $telegramMethod
     * @param array  $formParams
     *
     * @return string
     * @throws GuzzleException
     */
    private function send(string $httpMethod, string $telegramMethod, array $formParams): string
    {
        $response = $this->client->request(
            $httpMethod,
            sprintf('/bot%s/%s', $this->token, $telegramMethod),
            ['form_params' => $formParams]
        );

        return $response->getBody()->getContents();
    }
}
