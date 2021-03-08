<?php

declare(strict_types=1);

namespace App\Tests\Service\Registry;

use App\BotAction\BotActionInterface;
use App\Entity\Message\Data;

class TestAction implements BotActionInterface
{
    public function getCommand(): string
    {
        return '/test';
    }

    public function run(Data $data)
    {
        // TODO: Implement run() method.
    }
}
