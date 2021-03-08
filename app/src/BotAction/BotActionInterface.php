<?php

declare(strict_types=1);

namespace App\BotAction;

use App\Entity\Message\Data;

interface BotActionInterface
{
    public function getCommand(): string;

    public function run(Data $data);
}
