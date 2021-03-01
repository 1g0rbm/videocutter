<?php

declare(strict_types=1);

namespace App\Dto\Request;

class Webhook
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
