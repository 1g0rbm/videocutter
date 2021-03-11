<?php

declare(strict_types=1);

namespace App\Service\TgBot;

use App\Entity\Message\Response;

interface TgSendMessageInterface
{
    public function sendMessage(Response $response): string;
}