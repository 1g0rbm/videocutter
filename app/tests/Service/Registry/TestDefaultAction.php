<?php

declare(strict_types=1);

namespace App\Tests\Service\Registry;

use App\BotAction\BotActionInterface;
use App\Entity\Message\Data;
use App\Entity\Message\Response;

class TestDefaultAction implements BotActionInterface
{
    public function getCommand(): string
    {
        return '/no_command';
    }

    public function run(Data $data): Response
    {
        return new Response(1, 'response text');
    }
}
