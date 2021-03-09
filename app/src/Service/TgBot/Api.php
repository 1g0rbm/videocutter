<?php

declare(strict_types=1);

namespace App\Service\TgBot;

use GuzzleHttp\Client;

class Api
{
    private Client $client;

    private string $token;

    public function __construct(Client $client, string $token)
    {
        $this->client = $client;
        $this->token  = $token;
    }
}
